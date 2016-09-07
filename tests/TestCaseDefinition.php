<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 13:56
 */
namespace samsonframework\container\tests;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\DefinitionBuilder;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\ParameterDefinition;
use samsonframework\container\definition\PropertyDefinition;

class TestCaseDefinition extends TestCase
{
    /**
     * @param DefinitionBuilder $builder
     * @param string $className
     * @return ClassDefinition
     */
    public function getClassDefinition(DefinitionBuilder $builder, string $className)
    {
        /** @var ClassDefinition[] $collection */
        $collection = $this->getProperty('definitionCollection', $builder);
        return $collection[$className];
    }

    /**
     * @param DefinitionBuilder $builder
     * @param string $className
     * @param string $methodName
     * @return MethodDefinition
     */
    public function getMethodDefinition(DefinitionBuilder $builder, string $className, string $methodName)
    {
        $classDefinition = $this->getClassDefinition($builder, $className);
        $collection = $this->getProperty('methodsCollection', $classDefinition);
        return $collection[$methodName];
    }

    /**
     * @param DefinitionBuilder $builder
     * @param string $className
     * @param string $propertyName
     * @return PropertyDefinition
     */
    public function getPropertyDefinition(DefinitionBuilder $builder, string $className, string $propertyName)
    {
        $classDefinition = $this->getClassDefinition($builder, $className);
        $collection = $this->getProperty('propertiesCollection', $classDefinition);
        return $collection[$propertyName];
    }

    /**
     * @param DefinitionBuilder $builder
     * @param string $className
     * @param string $methodName
     * @param string $parameterName
     * @return ParameterDefinition
     */
    public function getParameterDefinition(
        DefinitionBuilder $builder,
        string $className,
        string $methodName,
        string $parameterName
    ) {
        $classDefinition = $this->getClassDefinition($builder, $className);
        $collection = $this->getProperty('methodsCollection', $classDefinition);
        $methodDefinition = $collection[$methodName];
        $collection = $this->getProperty('parametersCollection', $methodDefinition);
        return $collection[$parameterName];
    }
}