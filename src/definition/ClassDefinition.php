<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\exception\PropertyDefinitionAlreadyExistsException;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;

/**
 * Class ClassDefinition
 *
 * @package samsonframework\container\definition
 */
class ClassDefinition extends AbstractDefinition
{
    /** @var string Class name */
    protected $className;
    /** @var string Service name */
    protected $serviceName;
    /** @var MethodDefinition[] Methods collection */
    protected $methodsCollection = [];
    /** @var PropertyDefinition[] Property collection */
    protected $propertiesCollection = [];

    /**
     * ClassDefinition constructor.
     *
     * @param string $className
     * @param string $serviceName
     */
    public function __construct(string $className, string $serviceName = null)
    {
        $this->className = $className;
        $this->serviceName = $serviceName;
    }

    /**
     * Get class name
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Define class constructor arguments
     *
     * @param array $arguments
     * @return ClassDefinition
     * @throws MethodDefinitionAlreadyExistsException
     */
    public function defineArguments(array $arguments) : ClassDefinition
    {
        /** Add constructor method manually */
        $this->defineMethod('__construct', $arguments);

        return $this;
    }

    /**
     * Define method
     *
     * @param string $methodName
     * @param array $arguments
     * @return ClassDefinition
     * @throws MethodDefinitionAlreadyExistsException
     */
    public function defineMethod(string $methodName, array $arguments) : ClassDefinition
    {
        $methodDefinition = new MethodDefinition($this, $methodName);
        $methodDefinition->defineArguments($arguments)->end();

        if (array_key_exists($methodName, $this->methodsCollection)) {
            throw new MethodDefinitionAlreadyExistsException();
        }
        $this->methodsCollection[$methodName] = $methodDefinition;

        return $this;
    }

    /**
     * Define property
     *
     * @param string $propertyName
     * @param ReferenceInterface $value
     * @return ClassDefinition
     * @throws PropertyDefinitionAlreadyExistsException
     */
    public function defineProperty(string $propertyName, ReferenceInterface $value) : ClassDefinition
    {
        $propertyDefinition = new PropertyDefinition($this, $propertyName);
        $propertyDefinition->defineValue($value)->end();

        if (array_key_exists($propertyName, $this->propertiesCollection)) {
            throw new PropertyDefinitionAlreadyExistsException();
        }
        $this->propertiesCollection[$propertyName] = $propertyDefinition;

        return $this;
    }

    /** {@inheritdoc} */
    public function toMetadata() : ClassMetadata
    {
        $classMetadata = new ClassMetadata();
        $classMetadata->className = $this->className;
        $classMetadata->name = $this->serviceName ?? $this->className;

        // Resolve methods
        if (count($this->methodsCollection)) {
            foreach ($this->methodsCollection as $methodDefinition) {
                $classMetadata->methodsMetadata[$methodDefinition->getMethodName()] =
                    $methodDefinition->toMethodMetadata($classMetadata);
            }
        }
        // Resolve properties
        if (count($this->propertiesCollection)) {
            foreach ($this->propertiesCollection as $propertyDefinition) {
                $classMetadata->propertiesMetadata[$propertyDefinition->getPropertyName()] =
                    $propertyDefinition->toPropertyMetadata($classMetadata);
            }
        }
        return $classMetadata;
    }
}
