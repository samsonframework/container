<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container;

use samsonframework\container\ContainerInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\PropertyMetadata;
use samsonframework\di\Container;
use samsonphp\generator\Generator;

/**
 * Container builder.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Builder implements ContainerBuilderInterface
{
    /** Controller classes scope name */
    const SCOPE_CONTROLLER = 'controllers';

    /** Service classes scope name */
    const SCOPE_SERVICES = 'service';

    /** Generated resolving function name prefix */
    const DI_FUNCTION_PREFIX = 'container';

    /** Generated resolving function service static collection name */
    const DI_FUNCTION_SERVICES = self::SCOPE_SERVICES . 'Instances';

    /** @var string[] Collection of available container scopes */
    protected $scopes = [
        self::SCOPE_CONTROLLER => [],
        self::SCOPE_SERVICES => []
    ];

    /** @var ClassMetadata[] Collection of classes metadata */
    protected $classesMetadata = [];

    /** @var array Collection of dependencies aliases */
    protected $classAliases = [];

    /** @var  ContainerInterface */
    protected $parentContainer;

    /**
     * @var Generator
     */
    protected $generator;

    /** @var string Resolver function name */
    protected $resolverFunction;

    /**
     * Container builder constructor.
     *
     * @param Generator       $generator     PHP code generator
     * @param ClassMetadata[] $classMetadata Collection of classes metadata for container
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $classesMetadata, $containerClass = 'Container', $namespace = '', ContainerInterface $parentContainer = null)
    {
        $this->classesMetadata = $this->processClassMetadata($classesMetadata);
        $this->parentContainer = $parentContainer;

        // Build dependency injection container function name
        $this->resolverFunction = uniqid(self::DI_FUNCTION_PREFIX);

        $containerDependencies = [];
        foreach ($classesMetadata as $classMetadata) {
            $className = $classMetadata->className;
            // Store inner dependencies
            if (array_key_exists('__construct', $classMetadata->methodsMetadata)) {
                $containerDependencies[$className] = array_values($classMetadata->methodsMetadata['__construct']->dependencies ?? []);
            }
        }

        $this->generator
            ->text('<?php declare(strict_types = 1);')
            ->newLine()
            ->defNamespace($namespace)
            ->multiComment(['Application container'])
            ->defClass($containerClass, '\\' . Container::class)
            ->multiComment(['@var array Collection of service instances'])
            ->defClassFunction('__construct', 'public', [], ['Container constructor'])
            ->newLine('$this->dependencies = ')->arrayValue($containerDependencies)->text(';')
            ->newLine('$this->aliases = ')->arrayValue($this->classAliases)->text(';')
            ->newLine('$this->scopes = ')->arrayValue($this->scopes)->text(';')
            ->newLine('$this->services = ')->arrayValue($this->scopes[self::SCOPE_SERVICES])->text(';')
            ->endClassFunction()
            ->defClassFunction('logic', 'protected', ['$dependency'], ['{@inheritdoc}'])
            ->newLine('return $this->' . $this->resolverFunction . '($dependency);')
            ->endClassFunction();

        foreach ($this->classesMetadata as $classMetadata) {
            $className = $classMetadata->className;
            $dependencyName = $classMetadata->name ?? $className;

            // Generate camel case getter method
            $camelMethodName = 'get' . str_replace(' ', '', ucwords(ucfirst(str_replace(['\\', '_'], ' ', $dependencyName))));

            $this->generator
                ->defClassFunction($camelMethodName, 'public', [], ['@return ' . '\\'.ltrim($className, '\\') . ' Get ' . $dependencyName . ' instance'])
                ->newLine('return $this->' . $this->resolverFunction . '(\'' . $dependencyName . '\');')
                ->endClassFunction();
        }

        // Build di container function and add to container class and return class code
        $this->buildDependencyResolver($this->resolverFunction);

        return $this->generator
            ->endClass()
            ->flush();
    }

    /**
     * Read class metadata and fill internal collections.
     *
     * @param ClassMetadata[] $classesMetadata
     * @return ClassMetadata[] Processed class metadata
     */
    public function processClassMetadata(array $classesMetadata) : array
    {
        /** @var ClassMetadata[] $processedClassesMetadata */
        $processedClassesMetadata = [];

        // Read all classes in given file
        foreach ($classesMetadata as $classMetadata) {
            // Store by metadata name as alias
            $this->classAliases[$classMetadata->className] = $classMetadata->name;

            // Store class in defined scopes
            foreach ($classMetadata->scopes as $scope) {
                $this->scopes[$scope][$classMetadata->name] = $classMetadata->className;
            }
            $processedClassesMetadata[$classMetadata->name] = $classMetadata;
        }

        $dependencies = [];
        foreach ($processedClassesMetadata as $alias => $classMetadata) {
            if (count($classMetadata->methodsMetadata)) {
                foreach ($classMetadata->methodsMetadata as $methodMetadata) {
                    foreach ($methodMetadata->dependencies as $dependency) {
                        if (in_array($this->getArgumentType($dependency), [1, 2], true)) {
                            $dependencies[] = $dependency;
                        }
                    }
                }
                $dependencies[] = $classMetadata->name;
            }
            if (count($classMetadata->propertiesMetadata)) {
                foreach ($classMetadata->propertiesMetadata as $propertyMetadata) {
                    $dependencies[] = $propertyMetadata->dependency;
                }
                $dependencies[] = $classMetadata->name;
            }
            if (count($classMetadata->scopes)) {
                $dependencies[] = $classMetadata->name;
            }
        }
        $dependencies = array_unique($dependencies);

        foreach ($processedClassesMetadata as $alias => $classMetadata) {
            if (!in_array($alias, $dependencies, true)) {
                unset($processedClassesMetadata[$alias]);
            }
        }
        $this->generator->flush();

        return $processedClassesMetadata;
    }

    /**
     * Build dependency resolving function.
     *
     * @param string $functionName Function name
     *
     * @throws \InvalidArgumentException
     */
    protected function buildDependencyResolver($functionName)
    {
        $inputVariable = '$aliasOrClassName';
        $this->generator
            ->defClassFunction($functionName, 'protected', [$inputVariable], ['Dependency resolving function'])
            //->defVar('static ' . self::DI_FUNCTION_SERVICES . ' = []')
            ->newLine();

        // Generate all container and delegate conditions
        $this->generateConditions($inputVariable, false);

        // Add method not found
        $this->generator->endIfCondition()->endFunction();
    }

    /**
     * Generate logic conditions and their implementation for container and its delegates.
     *
     * @param string     $inputVariable Input condition parameter variable name
     * @param bool|false $started       Flag if condition branching has been started
     */
    public function generateConditions($inputVariable = '$alias', $started = false)
    {
        // Iterate all container dependencies
        foreach ($this->classesMetadata as $classMetadata) {
            $className = $classMetadata->className;
            // Generate condition statement to define if this class is needed
            $conditionFunc = !$started ? 'defIfCondition' : 'defElseIfCondition';

            // Output condition branch
            $this->generator->$conditionFunc(
                $this->buildResolverCondition($inputVariable, $className, $classMetadata->name)
            );

            // Define if this class has service scope
            $isService = in_array($className, $this->scopes[self::SCOPE_SERVICES], true);

            /** @var MethodMetadata[] Gather only valid method for container */
            $classValidMethods = $this->getValidClassMethodsMetadata($classMetadata->methodsMetadata);

            /** @var PropertyMetadata[] Gather only valid property for container */
            $classValidProperties = $this->getValidClassPropertiesMetadata($classMetadata->propertiesMetadata);

            // Define class or service variable
            $staticContainerName = $isService
                ? '$this->' . self::DI_FUNCTION_SERVICES . '[\'' . $classMetadata->name . '\']'
                : '$temp';

            if ($isService) {
                // Check if dependency was instantiated
                $this->generator->defIfCondition('!array_key_exists(\'' . $classMetadata->name . '\', $this->' . self::DI_FUNCTION_SERVICES . ')');
            }

            if (count($classValidMethods) || count($classValidProperties)) {
                $this->generator->newLine($staticContainerName . ' = ');
                $this->buildResolvingClassDeclaration($className);
                $this->buildConstructorDependencies($classMetadata->methodsMetadata);

                // Internal scope reflection variable
                $reflectionVariable = '$reflectionClass';

                $this->buildReflectionClass($className, $classValidProperties, $classValidMethods, $reflectionVariable);

                // Process class properties
                foreach ($classValidProperties as $property) {
                    // If such property has the dependency
                    if ($property->dependency) {
                        // Set value via refection
                        $this->buildResolverPropertyDeclaration(
                            $property->name,
                            $property->dependency,
                            $staticContainerName,
                            $reflectionVariable,
                            $property->isPublic
                        );
                    }
                }

                /** @var MethodMetadata $methodMetadata */
                foreach ($classValidMethods as $methodName => $methodMetadata) {
                    $this->buildResolverMethodDeclaration(
                        $methodMetadata->dependencies,
                        $methodName,
                        $staticContainerName,
                        $reflectionVariable,
                        $methodMetadata->isPublic
                    );
                }

                if ($isService) {
                    $this->generator->endIfCondition();
                }

                $this->generator->newLine()->newLine('return ' . $staticContainerName . ';');
            } else {
                if ($isService) {
                    $this->generator->newLine($staticContainerName.' = ');
                    $this->buildResolvingClassDeclaration($className);
                    $this->buildConstructorDependencies($classMetadata->methodsMetadata);
                    $this->generator->endIfCondition()->newLine('return ' . $staticContainerName . ';');
                } else {
                    $this->generator->newLine('return ');
                    $this->buildResolvingClassDeclaration($className);
                    $this->buildConstructorDependencies($classMetadata->methodsMetadata);
                }

            }

            // Set flag that condition is started
            $started = true;
        }
    }

    /**
     * Build resolving function condition.
     *
     * @param string      $inputVariable Condition variable
     * @param string      $className
     * @param string|null $alias
     *
     * @return string Condition code
     */
    protected function buildResolverCondition(string $inputVariable, string $className, string $alias = null) : string
    {
        // Create condition branch
        $condition = $inputVariable . ' === \'' . $className . '\'';

        if ($alias !== null && $alias !== $className) {
            $condition .= '||' . $this->buildResolverCondition($inputVariable, $alias);
        }

        return $condition;
    }

    /**
     * Get valid class methods metadata.
     *
     * @param MethodMetadata[] $classMethodsMetadata All class methods metadata
     *
     * @return array Valid class methods metadata
     */
    protected function getValidClassMethodsMetadata(array $classMethodsMetadata)
    {
        /** @var MethodMetadata[] Gather only valid method for container */
        $classValidMethods = [];
        foreach ($classMethodsMetadata as $methodName => $methodMetadata) {
            // Skip constructor method and empty dependencies
            if ($methodName !== '__construct' && count($methodMetadata->dependencies) > 0) {
                $classValidMethods[$methodName] = $methodMetadata;
            }
        }

        return $classValidMethods;
    }

    /**
     * Get valid class properties metadata.
     *
     * @param PropertyMetadata[] $classPropertiesMetadata All class properties metadata
     *
     * @return array Valid class properties metadata
     */
    protected function getValidClassPropertiesMetadata(array $classPropertiesMetadata)
    {
        /** @var PropertyMetadata[] Gather only valid property for container */
        $classValidProperties = [];
        foreach ($classPropertiesMetadata as $propertyName => $propertyMetadata) {
            // Skip constructor method and empty dependencies
            if ($propertyMetadata->dependency) {
                $classValidProperties[$propertyName] = $propertyMetadata;
            }
        }

        return $classValidProperties;
    }

    /**
     * Build resolving function class block.
     *
     * @param string $className Class name for new instance creation
     */
    protected function buildResolvingClassDeclaration(string $className)
    {
        $this->generator->text('new \\' . ltrim($className, '\\') . '(');
    }

    /**
     * Build constructor arguments injection.
     *
     * @param MethodMetadata[] $methodsMetaData
     */
    protected function buildConstructorDependencies(array $methodsMetaData)
    {
        // Process constructor dependencies
        $argumentsCount = 0;
        if (array_key_exists('__construct', $methodsMetaData)) {
            $constructorArguments = $methodsMetaData['__construct']->dependencies ?? [];
            $argumentsCount = count($constructorArguments);
            $i = 0;

            // Add indentation to move declaration arguments
            $this->generator->tabs++;

            if ($methodsMetaData['__construct'] && isset($methodsMetaData['__construct']->classMetadata)) {
                $className = $methodsMetaData['__construct']->classMetadata->className;
                $parameters = (new \ReflectionMethod($className, '__construct'))->getParameters();
                foreach ($parameters as $parameter) {
                    if (isset($constructorArguments[$parameter->getName()])) {
                        $this->buildResolverArgument($constructorArguments[$parameter->getName()]);
                        // Add comma if this is not last dependency
                        if (++$i < $argumentsCount) {
                            $this->generator->text(',');
                        }
                    }
                }
            }

            // Process constructor arguments
//            foreach ($constructorArguments as $argument => $parameterMetadata) {
//                $this->buildResolverArgument($parameterMetadata);
//
//                // Add comma if this is not last dependency
//                if (++$i < $argumentsCount) {
//                    $this->generator->text(',');
//                }
//            }

            // Restore indentation
            $this->generator->tabs--;
        }

        // Close declaration block, multiline if we have dependencies
        $argumentsCount ? $this->generator->newLine(');') : $this->generator->text(');');
    }

    /**
     * Build resolving function dependency argument.
     *
     * @param mixed $argument Dependency argument
     *
     * @throws \InvalidArgumentException On invalid argument type
     */
    protected function buildResolverArgument($argument, $textFunction = 'newLine')
    {
        switch ($this->getArgumentType($argument)) {
            // Call container logic for this dependency
            case 1:
                return $this->generator->$textFunction('$this->get(\'' . $argument . '\')');
            // Call container logic for this dependency by alias
            case 2:
                return $this->generator->$textFunction('$this->get(\'' . $this->classAliases[$argument] . '\')');
            // String variable
            case 3:
                return $this->generator->$textFunction()->stringValue($argument);
            // Dependency value is array
            case 4:
                return $this->generator->$textFunction()->arrayValue($argument);
        }
    }

    /**
     * Define argument type
     *
     * @param mixed $argument
     * @return int
     * @throws \InvalidArgumentException
     */
    protected function getArgumentType($argument) : int
    {
        // This is a dependency which invokes resolving function
        if (is_string($argument)) {
            if ($this->parentContainer !== null && $this->parentContainer->has($argument)) {
                return 1;
            } elseif (array_key_exists($argument, $this->classesMetadata)) {
                return 1;
            } elseif (array_key_exists($argument, $this->classAliases)) {
                return 2;
            } elseif (class_exists($argument)) { // If this argument is existing class
                throw new \InvalidArgumentException($argument.' class metadata is not defined');
            } elseif (interface_exists($argument)) { // If this argument is existing interface
                throw new \InvalidArgumentException($argument.' - interface dependency not resolvable');
            } else { // String variable
                return 3;
            }
        } elseif (is_array($argument)) { // Dependency value is array
            return 4;
        }

        return 0;
    }

    /**
     * Generate reflection class for private/protected methods or properties
     * in current scope.
     *
     * @param string             $className          Reflection class source class name
     * @param PropertyMetadata[] $propertiesMetadata Properties metadata
     * @param MethodMetadata[]   $methodsMetadata    Methods metadata
     * @param string             $reflectionVariable Reflection class variable name
     */
    protected function buildReflectionClass(string $className, array $propertiesMetadata, array $methodsMetadata, string $reflectionVariable)
    {
        /**
         * Iterate all properties and create internal scope reflection class instance if
         * at least one property in not public
         */
        foreach ($propertiesMetadata as $propertyMetadata) {
            if (!$propertyMetadata->isPublic) {
                $this->generator
                    ->comment('Create reflection class for injecting private/protected properties and methods')
                    ->newLine($reflectionVariable . ' = new \ReflectionClass(\'' . $className . '\');')
                    ->newLine();

                return true;
            }
        }

        /**
         * Iterate all properties and create internal scope reflection class instance if
         * at least one property in not public
         */
        foreach ($methodsMetadata as $methodMetadata) {
            if (!$methodMetadata->isPublic) {
                $this->generator
                    ->comment('Create reflection class for injecting private/protected properties and methods')
                    ->newLine($reflectionVariable . ' = new \ReflectionClass(\'' . $className . '\');')
                    ->newLine();

                return true;
            }
        }

        return false;
    }

    /**
     * Build resolving property injection declaration.
     *
     * @param string $propertyName       Target property name
     * @param string $dependency         Dependency class name
     * @param string $containerVariable  Container declaration variable name
     * @param string $reflectionVariable Reflection class variable name
     * @param bool   $isPublic           Flag if property is public
     */
    protected function buildResolverPropertyDeclaration(
        string $propertyName,
        string $dependency,
        string $containerVariable,
        string $reflectionVariable,
        bool $isPublic
    )
    {
        if ($isPublic) {
            $this->generator
                ->comment('Inject public dependency for $' . $propertyName)
                ->newLine($containerVariable . '->' . $propertyName . ' = ');
            $this->buildResolverArgument($dependency, 'text');
            $this->generator->text(';');
        } else {
            $this->generator
                ->comment('Inject private dependency for $' . $propertyName)
                ->newLine('$property = ' . $reflectionVariable . '->getProperty(\'' . $propertyName . '\');')
                ->newLine('$property->setAccessible(true);')
                ->newLine('$property->setValue(')
                ->increaseIndentation()
                ->newLine($containerVariable . ',');

            $this->buildResolverArgument($dependency);

            $this->generator
                ->decreaseIndentation()
                ->newLine(');')
                ->newLine('$property->setAccessible(false);')
                ->newLine();
        }
    }

    /**
     * Build resolving method injection declaration.
     *
     * @param array  $dependencies       Collection of method dependencies
     * @param string $methodName         Method name
     * @param string $containerVariable  Container declaration variable name
     * @param string $reflectionVariable Reflection class variable name
     * @param bool   $isPublic           Flag if method is public
     */
    protected function buildResolverMethodDeclaration(
        array $dependencies,
        string $methodName,
        string $containerVariable,
        string $reflectionVariable,
        bool $isPublic
    )
    {
        // Get method arguments
        $argumentsCount = count($dependencies);

        $this->generator->comment('Invoke ' . $methodName . '() and pass dependencies(y)');

        if ($isPublic) {
            $this->generator->newLine($containerVariable . '->' . $methodName . '(')->increaseIndentation();
        } else {
            $this->generator
                ->newLine('$method = ' . $reflectionVariable . '->getMethod(\'' . $methodName . '\');')
                ->newLine('$method->setAccessible(true);')
                ->newLine('$method->invoke(')
                ->increaseIndentation()
                ->newLine($containerVariable . ',');
        }

        $i = 0;
        // Iterate method arguments
        foreach ($dependencies as $argument => $dependency) {
            // Add dependencies
            $this->buildResolverArgument($dependency);

            // Add comma if this is not last dependency
            if (++$i < $argumentsCount) {
                $this->generator->text(',');
            }
        }

        $this->generator->decreaseIndentation()->newLine(');');
    }
}
