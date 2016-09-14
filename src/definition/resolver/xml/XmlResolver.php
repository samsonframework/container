<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 12.09.2016
 * Time: 6:33
 */
namespace samsonframework\container\definition\resolver\xml;

use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\exception\ClassDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\ParameterDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\PropertyDefinitionAlreadyExistsException;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\reference\BoolReference;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\FloatReference;
use samsonframework\container\definition\reference\IntegerReference;
use samsonframework\container\definition\reference\NullReference;
use samsonframework\container\definition\reference\ParameterReference;
use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\definition\reference\ServiceReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\definition\resolver\exception\ReferenceNotImplementsException;

/**
 * Class XmlResolver
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class XmlResolver
{
    /** How parameter presents in xml code */
    const PARAMETERS_KEY = 'parameters';
    /** How dependencies presents in xml code */
    const DEPENDENCIES_KEY = 'dependencies';
    /** How instance presents in xml code */
    const INSTANCE_KEY = 'definition';

    /**
     * Resolve xml code
     *
     * @param DefinitionBuilder $definitionBuilder
     * @param $xml
     *
     * @throws ClassDefinitionAlreadyExistsException
     * @throws \InvalidArgumentException
     * @throws PropertyDefinitionAlreadyExistsException
     * @throws ReferenceNotImplementsException
     * @throws MethodDefinitionAlreadyExistsException
     * @throws ParameterDefinitionAlreadyExistsException
     * @throws \samsonframework\container\definition\parameter\exception\ParameterAlreadyExistsException
     */
    public function resolve(DefinitionBuilder $definitionBuilder, $xml)
    {
        /**
         * Iterate config and resolve single instance
         *
         * @var string $key
         * @var array $arrayData
         */
        foreach ($this->xml2array(new \SimpleXMLElement($xml)) as $key => $arrayData) {
            // Resolve parameters
            if ($key === self::PARAMETERS_KEY) {
                // Define parameters
                foreach ($arrayData as $parameterKey => $parameterArray) {
                    $definitionBuilder->defineParameter($parameterKey, $this->resolveDependency($parameterArray));
                }
            }
            // Resolve dependencies
            if ($key === self::DEPENDENCIES_KEY) {
                // Iterate instances
                foreach ($arrayData as $dependencyKey => $definitionsArrayData) {
                    // Get only definition instances
                    if ($dependencyKey === self::INSTANCE_KEY) {
                        /**
                         * If we have only one instance we need to add array
                         * @var array $collection
                         */
                        $collection = !array_key_exists(0,
                            $definitionsArrayData) ? [$definitionsArrayData] : $definitionsArrayData;
                        /**
                         * Iterate collection of instances
                         * @var array $definitionsArrayData
                         */
                        foreach ($collection as $definitionArrayData) {
                            /**
                             * Create class definition
                             * @var ClassDefinition $classDefinition
                             */
                            $classDefinition = $definitionBuilder->addDefinition($definitionArrayData['@attributes']['class']);
                            // Resolve constructor
                            if (array_key_exists('constructor', $definitionArrayData)) {
                                $this->resolveConstructor($classDefinition, $definitionArrayData['constructor']);
                            }
                            // Resolve methods
                            if (array_key_exists('methods', $definitionArrayData)) {
                                // Iteare methods
                                foreach ($definitionArrayData['methods'] as $methodName => $methodArray) {
                                    $this->resolveMethod($classDefinition, $methodArray, $methodName);
                                }
                            }
                            // Resolve properties
                            if (array_key_exists('properties', $definitionArrayData)) {
                                // Iterate properties
                                foreach ($definitionArrayData['properties'] as $propertyName => $propertyArray) {
                                    $this->resolveProperty($classDefinition, $propertyArray, $propertyName);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Resolve constructor
     *
     * @param ClassDefinition $classDefinition
     * @param array $constructorArray
     * @throws MethodDefinitionAlreadyExistsException
     * @throws \InvalidArgumentException
     * @throws ParameterDefinitionAlreadyExistsException
     * @throws ReferenceNotImplementsException
     */
    public function resolveConstructor(ClassDefinition $classDefinition, array $constructorArray)
    {
        $methodDefinition = $classDefinition->defineConstructor();
        if (array_key_exists('arguments', $constructorArray)) {
            $this->resolveArguments($methodDefinition, $constructorArray['arguments']);
        }
    }

    /**
     * Resolve property
     *
     * @param ClassDefinition $classDefinition
     * @param array $propertyArray
     * @param string $propertyName
     * @throws \InvalidArgumentException
     * @throws ReferenceNotImplementsException
     * @throws PropertyDefinitionAlreadyExistsException
     */
    public function resolveProperty(ClassDefinition $classDefinition, array $propertyArray, string $propertyName)
    {
        $propertyDefinition = $classDefinition->defineProperty($propertyName);
        $propertyDefinition->defineDependency($this->resolveDependency($propertyArray));
    }

    /**
     * Resolve method
     *
     * @param ClassDefinition $classDefinition
     * @param array $methodArray
     * @param string $methodName
     * @throws MethodDefinitionAlreadyExistsException
     * @throws \InvalidArgumentException
     * @throws ParameterDefinitionAlreadyExistsException
     * @throws ReferenceNotImplementsException
     */
    public function resolveMethod(ClassDefinition $classDefinition, array $methodArray, string $methodName)
    {
        $methodDefinition = $classDefinition->defineMethod($methodName);
        if (array_key_exists('arguments', $methodArray)) {
            $this->resolveArguments($methodDefinition, $methodArray['arguments']);
        }
    }

    /**
     * Resolve method/constructor arguments
     *
     * @param MethodDefinition $methodDefinition
     * @param array $arguments
     * @throws ParameterDefinitionAlreadyExistsException
     * @throws \InvalidArgumentException
     * @throws ReferenceNotImplementsException
     */
    public function resolveArguments(MethodDefinition $methodDefinition, array $arguments)
    {
        foreach ($arguments as $argumentName => $argumentValue) {
            $methodDefinition
                ->defineParameter($argumentName)
                    ->defineDependency($this->resolveDependency($argumentValue));
        }
    }

    /**
     * Resolve dependency
     *
     * @param $data
     * @return ReferenceInterface
     * @throws \InvalidArgumentException
     * @throws ReferenceNotImplementsException
     */
    public function resolveDependency($data): ReferenceInterface
    {
        // Get value type
        $type = $data['@attributes']['type'] ?? 'string';
        // Get value
        $value = $data['@attributes']['value'] ?? null;
        // When that is not a collection then value can not be null
        if ($type !== 'collection' && $value === null) {
            throw new \InvalidArgumentException(sprintf('Value for type "%s" have to be specified', $type));
        }
        // Resolve type
        switch ($type) {
            case 'text':
            case 'string':
                return new StringReference($value);
            case 'int':
            case 'integer':
                return new IntegerReference($value);
            case 'float':
                return new FloatReference($value);
            case 'boolean':
            case 'bool':
                return new BoolReference($value);
            case 'class':
                return new ClassReference($value);
            case 'constant':
                return new ConstantReference($value);
            case 'service':
                return new ServiceReference($value);
            case 'null':
                return new NullReference();
            case 'parameter':
                return new ParameterReference($value);
            case 'collection':
                return $this->resolveCollection($data);
            default:
                throw new ReferenceNotImplementsException();
        }
    }

    /**
     * Resolve collection type
     *
     * @param array $data
     * @return CollectionReference
     * @throws ReferenceNotImplementsException
     * @throws \InvalidArgumentException
     */
    public function resolveCollection(array $data): CollectionReference
    {
        $collection = new CollectionReference();
        if (array_key_exists('item', $data)) {
            /** @var array $itemCollection */
            $itemCollection = array_key_exists('key', $data['item']) && array_key_exists('value', $data['item'])
                ? [$data['item']]
                : $data['item'];
            /** @var array $item */
            foreach ($itemCollection as $item) {
                $collection->addItem(new CollectionItem(
                    $this->resolveDependency($item['key']),
                    $this->resolveDependency($item['value'])
                ));
            }
        }
        return $collection;
    }

    /**
     * Convert xml to array
     *
     * @param $xmlObject
     * @param array $out
     * @return array
     */
    protected function xml2array($xmlObject, array $out = []): array
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = (is_object($node) || is_array($node)) ? $this->xml2array($node) : $node;
        }
        return $out;
    }
}
