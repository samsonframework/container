<?php declare(strict_types=1);
/**
 * Created by Ruslan Molodyko.
 * Date: 09.09.2016
 * Time: 7:19
 */
namespace samsonframework\container\definition\builder;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\builder\exception\ReferenceNotImplementsException;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\NullReference;
use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\definition\reference\ServiceReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\generator\ClassGenerator;

/**
 * Class DefinitionCompiler
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class DefinitionGenerator
{
    /** @var  ClassGenerator */
    protected $generator;

    /**
     * DefinitionCompiler constructor.
     *
     * @param ClassGenerator $classGenerator
     */
    public function __construct(ClassGenerator $classGenerator)
    {
        $this->generator = $classGenerator;
    }

    /**
     * Get class generator
     *
     * @return ClassGenerator
     */
    public function getClassGenerator()
    {
        return $this->generator;
    }

    /**
     * Compile and get container
     *
     * @param ClassDefinition[] $classDefinitionCollection
     * @return string Get container code
     * @throws ReferenceNotImplementsException
     * @throws \InvalidArgumentException
     */
    public function generateClass(array $classDefinitionCollection): string
    {
        $methodGenerator = $this->generator
            ->defMethod('logic')
            ->defProtected()
            ->defArgument('classNameOrServiceName')
            ->defLine('static $singletonCollection = [];')
        ;

        $isFirstCondition = true;
        /** @var ClassDefinition $classDefinition */
        foreach ($classDefinitionCollection as $classDefinition) {

            $className = $classDefinition->getClassName();
            $serviceName = $classDefinition->getServiceName();
            // Name for static service collection
            $serviceId = $serviceName ?? $className;

            // Generate if condition by class name or value
            $methodGenerator->defLine($this->generateStartIfCondition($isFirstCondition, $className, $serviceName));

            // Generate static property service access if service is singleton
            // TODO Move this from if condition
            if ($classDefinition->isSingleton()) {
                $methodGenerator->defLine($this->generateStaticFunctionCall($serviceId));
            }

            // Generate constructor call if not
            $methodGenerator->defLine($this->generateConstructor($classDefinition));

            // Generate methods
            foreach ($classDefinition->getMethodsCollection() as $methodDefinition) {
                // Constructor is not a method skip it
                if ($methodDefinition->getMethodName() !== '__construct') {
                    $methodGenerator->defLine($this->generateSetters($classDefinition, $methodDefinition));
                }
            }

            // Generate properties
            foreach ($classDefinition->getPropertiesCollection() as $propertyDefinition) {
                // Generate properties
                $methodGenerator->defLine($this->generateProperty($classDefinition, $propertyDefinition));
            }

            // Generate return operator
            $methodGenerator->defLine($this->generateReturnOperator($classDefinition, $serviceId));

            // Close if
            $methodGenerator->defLine($this->generateEndIfCondition());

            $isFirstCondition = false;
        }

        // Close method
        $methodGenerator->end();

        return "<?php \n" . $this->generator->code();
    }

    /**
     * Generate start if condition with smart if/else creation
     *
     * @param bool $isFirstCondition
     * @param string $className
     * @param string|null $serviceName
     * @return string
     */
    protected function generateStartIfCondition(bool $isFirstCondition, string $className, string $serviceName = null): string
    {
        // If call this method first time then generate simple if or elseif construction
        $ifCondition = $isFirstCondition ? 'if' : 'elseif';
        // If service name exists then add it to condition
        $serviceCondition = $serviceName ? "\$classNameOrServiceName === '$serviceName' || " : '';
        return "$ifCondition ($serviceCondition\$classNameOrServiceName === '$className') {";
    }

    /**
     * Close if condition
     *
     * @return string
     */
    protected function generateEndIfCondition(): string
    {
        return '}';
    }

    /**
     * When this service is singleton then create if with call already defined instance
     *
     * @param string $id Class or service name
     * @return string
     */
    protected function generateStaticFunctionCall(string $id): string
    {
        return "\tif (array_key_exists('$id', \$singletonCollection)) " .
        "{\n\t\t\t\treturn  \$singletonCollection['$id'];\n\t\t\t}";
    }

    /**
     * Generate constructor for service
     *
     * @param ClassDefinition $classDefinition
     * @return string
     * @throws ReferenceNotImplementsException
     */
    protected function generateConstructor(ClassDefinition $classDefinition): string
    {
        // TODO Fix adding slash before namespace
        $className = '\\' . $classDefinition->getClassName();
        $arguments = '';
        if (array_key_exists('__construct', $classDefinition->getMethodsCollection())) {
            $arguments .= $this->generateArguments($classDefinition->getMethodsCollection()['__construct']);
        }
        return "\t\$temp = new $className($arguments);";
    }

    /**
     * Generate arguments for method
     *
     * @param MethodDefinition $methodDefinition
     * @return string
     * @throws ReferenceNotImplementsException
     */
    protected function generateArguments(MethodDefinition $methodDefinition): string
    {
        $parameterCollection = $methodDefinition->getParametersCollection();

        // If arguments more than one then generate this ones on new line
        $newLinePrefix = count($parameterCollection) > 1 ? "\n\t\t\t\t" : '';

        $arguments = '';
        // Iterate all parameters and generate arguments
        foreach ($parameterCollection as $parameterDefinition) {
            $dependencyValue = $this->resolveDependency($parameterDefinition->getDependency());
            $arguments .= $newLinePrefix . "$dependencyValue,";
        }
        // Remove comma
        if (count($parameterCollection)) {
            $arguments = rtrim($arguments, ',');
        }
        // Add tabs
        if (count($parameterCollection) > 1) {
            $arguments .= "\n\t\t\t";
        }
        return $arguments;
    }

    /**
     * Generate setters for class
     *
     * @param ClassDefinition $classDefinition
     * @param MethodDefinition $methodDefinition
     * @return string
     * @throws ReferenceNotImplementsException
     */
    protected function generateSetters(ClassDefinition $classDefinition, MethodDefinition $methodDefinition): string
    {
        $className = $classDefinition->getClassName();
        $methodName = $methodDefinition->getMethodName();
        $arguments = $this->generateArguments($methodDefinition);
        // Call method by reflection
        if (!$methodDefinition->isPublic()) {
            $isEmptyArguments = count($methodDefinition->getParametersCollection()) === 0;
            return "\t\$method = (new \\ReflectionClass('$className'))->getMethod('$methodName');" .
            "\n\t\t\t\$method->setAccessible(true);" .
            "\n\t\t\t\$method->invoke(\$temp" . ($isEmptyArguments ? '' : ", $arguments") . ');' .
            "\n\t\t\t\$method->setAccessible(false);";
        } else {
            return "\t\$temp->$methodName($arguments);";
        }
    }

    /**
     * Generate property for class
     *
     * @param PropertyDefinition $propertyDefinition
     * @param ClassDefinition $classDefinition
     * @return string
     * @throws ReferenceNotImplementsException
     */
    protected function generateProperty(ClassDefinition $classDefinition, PropertyDefinition $propertyDefinition): string
    {
        $dependencyValue = $this->resolveDependency($propertyDefinition->getDependency());
        $propertyName = $propertyDefinition->getPropertyName();
        $className = $classDefinition->getClassName();

        if ($propertyDefinition->isPublic()) {
            return "\t\$temp->$propertyName = $dependencyValue;";
            // Use reflection to setting the value
        } else {
            return "\t\$property = (new \\ReflectionClass('$className'))->getProperty('$propertyName');" .
                "\n\t\t\t\$property->setAccessible(true);" .
                "\n\t\t\t\$property->setValue(\$temp, $dependencyValue);" .
                "\n\t\t\t\$property->setAccessible(false);";
        }
    }

    /**
     * Generate return operator and save instance to static properties it its singleton
     *
     * @param ClassDefinition $classDefinition
     * @param string $id
     * @return string
     */
    protected function generateReturnOperator(ClassDefinition $classDefinition, string $id = null): string
    {
        $code = "\treturn \$temp;";
        // If there is a singleton then save it to static service collection
        if ($classDefinition->isSingleton()) {
            $code = "\t\$singletonCollection['$id'] = \$temp;\n\t\t" . $code;
        }
        return $code;
    }

    /**
     * Resolve ReferenceInterface argument into value
     *
     * @param ReferenceInterface $reference
     * @return string
     * @throws ReferenceNotImplementsException
     */
    protected function resolveDependency(ReferenceInterface $reference): string
    {
        if ($reference instanceof ClassReference) {
            $value = $reference->getClassName();
            return "\$this->logic('$value')";
        } elseif ($reference instanceof NullReference) {
            return 'null';
        } elseif ($reference instanceof ServiceReference) {
            $value = $reference->getName();
            return "\$this->logic('$value')";
        } elseif ($reference instanceof StringReference) {
            $value = $reference->getValue();
            return "'$value'";
        } elseif ($reference instanceof ConstantReference) {
            $value = $reference->getValue();
            return "$value";
        } elseif ($reference instanceof CollectionReference) {
            /** @var array $value */
            $value = $reference->getCollection();
            $string = '[';
            // Iterate items
            foreach ($value as $key => $item) {
                // If item is the collection item then resolve it
                if ($item instanceof CollectionItem) {
                    $value = $item->getValue();
                    $key = $item->getKey();
                    // If there is another reference then resolve it
                    if (is_object($value) && ($value instanceof ReferenceInterface)) {
                        $value = $this->resolveDependency($value);
                        // If its simple string then surround it
                    } elseif (is_string($value)) {
                        $value = "'$value'";
                    }
                    // If there is another reference then resolve it
                    if (is_object($key) && ($key instanceof ReferenceInterface)) {
                        $key = $this->resolveDependency($key);
                        // If its simple string then surround it
                    } elseif (is_string($key)) {
                        $key = "'$key'";
                    }
                    $string .= "$key => $value, ";
                    // There is simple array
                } else {
                    // If its simple string then surround it
                    if (is_string($key)) {
                        $key = "'$key'";
                    }
                    // Resolve item
                    if ($item instanceof ReferenceInterface) {
                        $item = $this->resolveDependency($item);
                        // If its simple string then surround it
                    } elseif (is_string($item)) {
                        $item = "'$item'";
                    }
                    $string .= "$key => $item, ";
                }
            }
            // Remove comma
            $string = rtrim($string, ', ');
            return $string . ']';
        } else {
            throw new ReferenceNotImplementsException(sprintf(
                'Class "%s" does not have correct implementation in generator', get_class($reference)
            ));
        }
    }
}
