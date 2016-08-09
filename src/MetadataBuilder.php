<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container;

use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\resolver\ResolverInterface;
use samsonframework\di\Container;
use samsonframework\filemanager\FileManagerInterface;
use samsonphp\generator\Generator;

/**
 * Class Container.
 */
class MetadataBuilder
{
    /** Controller classes scope name */
    const SCOPE_CONTROLLER = 'controllers';

    /** Service classes scope name */
    const SCOPE_SERVICES = 'services';

    /** Generated resolving function name prefix */
    const DI_FUNCTION_PREFIX = 'container';

    /** Generated resolving function service static collection name */
    const DI_FUNCTION_SERVICES = '$' . self::SCOPE_SERVICES;

    /** @var string[] Collection of available container scopes */
    protected $scopes = [
        self::SCOPE_CONTROLLER => [],
        self::SCOPE_SERVICES => []
    ];

    /** @var ClassMetadata[] Collection of classes metadata */
    protected $classMetadata = [];

    /** @var FileManagerInterface */
    protected $fileManger;

    /** @var ResolverInterface */
    protected $classResolver;

    /** @var Generator */
    protected $generator;

    /** @var string Resolver function name */
    protected $resolverFunction;

    /**
     * Container constructor.
     *
     * @param FileManagerInterface $fileManger
     * @param ResolverInterface    $classResolver
     * @param Generator            $generator
     */
    public function __construct(
        FileManagerInterface $fileManger,
        ResolverInterface $classResolver,
        Generator $generator
    )
    {
        $this->fileManger = $fileManger;
        $this->classResolver = $classResolver;
        $this->generator = $generator;
    }

    /**
     * Load classes from paths.
     *
     * @param array $paths Paths for importing
     *
     * @return $this
     */
    public function loadFromPaths(array $paths)
    {
        // Iterate all paths and get files
        foreach ($this->fileManger->scan($paths, ['php']) as $phpFile) {
            // Read all classes in given file
            $this->loadFromClassNames($this->getDefinedClasses(require_once($phpFile)));
        }

        return $this;
    }

    /**
     * Load classes from class names collection.
     *
     * @param string[] $classes Collection of class names for resolving
     *
     * @return $this
     */
    public function loadFromClassNames(array $classes)
    {
        // Read all classes in given file
        foreach ($classes as $className) {
            // Resolve class metadata
            $this->classMetadata[$className] = $this->classResolver->resolve(new \ReflectionClass($className));
            // Store class in defined scopes
            foreach ($this->classMetadata[$className]->scopes as $scope) {
                $this->scopes[$scope][] = $className;
            }
        }

        return $this;
    }

    /**
     * Find class names defined in PHP code.
     *
     * @param string $php PHP code for scanning
     *
     * @return string[] Collection of found class names in php code
     */
    protected function getDefinedClasses($php) : array
    {
        $classes = array();

        // Append php marker for parsing file
        $php = strpos(is_string($php) ? $php : '', '<?php') !== 0 ? '<?php ' . $php : $php;

        $tokens = token_get_all($php);

        for ($i = 2, $count = count($tokens); $i < $count; $i++) {
            if ($tokens[$i - 2][0] === T_CLASS
                && $tokens[$i - 1][0] === T_WHITESPACE
                && $tokens[$i][0] === T_STRING
            ) {
                $classes[] = $tokens[$i][1];
            }
        }

        return $classes;
    }

    /**
     * Load classes from PHP code.
     *
     * @param string $php PHP code
     *
     * @return $this
     */
    public function loadFromCode($php)
    {
        if (count($classes = $this->getDefinedClasses($php))) {
            // TODO: Consider writing cache file and require it
            eval($php);
            $this->loadFromClassNames($classes);
        }

        return $this;
    }

    /**
     * Build container class.
     *
     * @param string|null $containerClass Container class name
     * @param string      $namespace      Name space
     *
     * @return string Generated Container class code
     * @throws \InvalidArgumentException
     */
    public function build($containerClass = 'Container', $namespace = '')
    {
        // Build dependency injection container function name
        $this->resolverFunction = uniqid(self::DI_FUNCTION_PREFIX);

        $this->generator
            ->text('<?php declare(strict_types = 1);')
            ->newLine()
            ->defNamespace($namespace)
            ->multiComment(['Application container'])
            ->defClass($containerClass, '\\' . Container::class)
            ->commentVar('array', 'Loaded dependencies')
            ->defClassVar('$dependencies', 'protected', array_keys($this->classMetadata))
            ->commentVar('array', 'Loaded services')
            ->defClassVar('$' . self::SCOPE_SERVICES, 'protected', $this->scopes[self::SCOPE_SERVICES])
            ->defClassFunction('logic', 'protected', ['$dependency'], ['Overridden dependency resolving function'])
            ->newLine('return $this->' . $this->resolverFunction . '($dependency);')
            ->endClassFunction();

        foreach ($this->classMetadata as $className => $classMetadata) {
            $dependencyName = $classMetadata->name ?? $className;

            // Generate camel case getter method
            $camelMethodName = 'get' . str_replace(' ', '', ucwords(ucfirst(str_replace(['\\', '_'], ' ', $dependencyName))));

            $this->generator
                ->defClassFunction($camelMethodName, 'public', [], ['@return \\' . $dependencyName . ' Get ' . $dependencyName . ' instance'])
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
            ->defVar('static ' . self::DI_FUNCTION_SERVICES . ' = []')
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
        foreach ($this->classMetadata as $className => $classMetadata) {
            // Generate condition statement to define if this class is needed
            $conditionFunc = !$started ? 'defIfCondition' : 'defElseIfCondition';

            // Output condition branch
            $this->generator->$conditionFunc(
                $this->buildResolverCondition($inputVariable, $className, $classMetadata->name)
            );

            // Define if this class has service scope
            $isService = in_array($className, $this->scopes[self::SCOPE_SERVICES], true);

            // Define class or service variable
            $staticContainerName = $isService
                ? self::DI_FUNCTION_SERVICES . '[\'' . $classMetadata->name . '\']'
                : '$temp';

            if ($isService) {
                // Check if dependency was instantiated
                $this->generator->defIfCondition('!array_key_exists(\'' . $className . '\', ' . self::DI_FUNCTION_SERVICES . ')');
            }

            $this->generator->newLine($staticContainerName . ' = ');
            $this->buildResolvingClassDeclaration($className);

            // Process constructor dependencies
            $argumentsCount = 0;
            if (array_key_exists('__construct', $classMetadata->methodsMetadata)) {
                $constructorArguments = $classMetadata->methodsMetadata['__construct']->dependencies;
                $argumentsCount = count($constructorArguments);
                $i = 0;

                // Add indentation to move declaration arguments
                $this->generator->tabs++;

                // Process constructor arguments
                foreach ($constructorArguments as $argument => $dependency) {
                    $this->buildResolverArgument($dependency);

                    // Add comma if this is not last dependency
                    if (++$i < $argumentsCount) {
                        $this->generator->text(',');
                    }
                }

                // Restore indentation
                $this->generator->tabs--;
            }

            // Close declaration block, multiline if we have dependencies
            $argumentsCount ? $this->generator->newLine(');') : $this->generator->text(');');
            $this->generator->newLine();

            // Process property dependencies
            if ($propertyCount = count($classMetadata->propertiesMetadata)) {
                $isCreatedReflectionClass = false;
                // Process constructor arguments
                foreach ($classMetadata->propertiesMetadata as $property) {
                    // If such property has the dependency
                    if ($property->dependency) {
                        // Set value via refection
                        $this->buildResolverPropertyDeclaration(
                            $className,
                            $property->name,
                            $property->dependency,
                            $staticContainerName,
                            $property->isPublic,
                            $isCreatedReflectionClass
                        );
                    }
                }
            }

            // Process method dependencies
            if (count($classMetadata->methodsMetadata)) {
                /**
                 * Iterate methods
                 * @var string $methodName
                 * @var MethodMetadata $methodMetadata
                 */
                foreach ($classMetadata->methodsMetadata as $methodName => $methodMetadata) {
                    // Skip constructor method and empty dependencies
                    if ($methodName === '__construct' || !count($methodMetadata->dependencies)) {
                        continue;
                    }

                    $this->generator->newLine();
                    $argumentsCount = count($methodMetadata->dependencies);

                    //TODO: Check if method is private or protected and create reflection class otherwise simply set property value to instance
                    if (!$isCreatedReflectionClass) {
                        $this->generator->newLine('$reflectionClass = new \ReflectionClass(\'' . $className . '\');');
                        $isCreatedReflectionClass = true;
                    }
                    // Set accessible
                    $this->generator->newLine('$reflectionClass->getMethod(\'' . $methodName. '\')->setAccessible(true);');
                    // Call method with dependencies
                    $this->generator->newLine('$reflectionClass->getMethod(\'' . $methodName . '\')->invoke(' . $staticContainerName . ', ');
                    $this->generator->tabs++;

                    $i = 0;
                    // Iterate method arguments
                    foreach ($methodMetadata->dependencies as $argument => $dependency) {
                        // Add dependencies
                        $this->buildResolverArgument($dependency);

                        // Add comma if this is not last dependency
                        if (++$i < $argumentsCount) {
                            $this->generator->text(',');
                        }
                    }
                    $this->generator->tabs--;
                    // Close method calling
                    $this->generator->newLine(');');
                }
            }

            if ($isService) {
                $this->generator->endIfCondition();
            }

            $this->generator->newLine('return ' . $staticContainerName . ';');

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
     * Build resolving function class block.
     *
     * @param string $className Class name for new instance creation
     */
    protected function buildResolvingClassDeclaration(string $className)
    {
        $this->generator->text('new \\' . ltrim($className, '\\') . '(');
    }

    /**
     * Build resolving function dependency argument.
     *
     * @param mixed $argument Dependency argument
     */
    protected function buildResolverArgument($argument, $textFunction = 'newLine')
    {
        // This is a dependency which invokes resolving function
        if (array_key_exists($argument, $this->classMetadata)) {
            // Call container logic for this dependency
            $this->generator->$textFunction('$this->' . $this->resolverFunction . '(\'' . $argument . '\')');
        } elseif (is_string($argument)) { // String variable
            $this->generator->$textFunction()->stringValue($argument);
        } elseif (is_array($argument)) { // Dependency value is array
            $this->generator->$textFunction()->arrayValue($argument);
        }
    }

    /**
     * Build resolving property declaration.
     *
     * @param string $className
     * @param string $propertyName
     * @param string $dependency
     * @param string $containerVariable
     * @param bool   $isCreatedReflectionClass
     *
     * @return string
     */
    protected function buildResolverPropertyDeclaration(
        string $className,
        string $propertyName,
        string $dependency,
        string $containerVariable,
        bool $isPublic,
        bool &$isCreatedReflectionClass
    )
    {
        $this->generator->comment('Inject dependency for $' . $propertyName);

        if ($isPublic) {
            $this->generator->newLine($containerVariable . '->' . $propertyName . ' = ');
            $this->buildResolverArgument($dependency, 'text');
            $this->generator->text(';');
        } else {
            // Create reflection class only once
            if (!$isCreatedReflectionClass) {
                $this->generator->newLine('$reflectionClass = new \ReflectionClass(\'' . $className . '\');');
                $isCreatedReflectionClass = true;
            }

            $this->generator
                ->newLine('$property = $reflectionClass->getProperty(\'' . $propertyName . '\');')
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
}
