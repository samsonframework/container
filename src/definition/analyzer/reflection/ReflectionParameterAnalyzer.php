<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\reflection;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\ParameterAnalyzerInterface;
use samsonframework\container\definition\builder\exception\ReferenceNotImplementsException;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\exception\MethodDefinitionNotFoundException;
use samsonframework\container\definition\exception\ParameterDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\ParameterDefinitionNotFoundException;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\ParameterDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\UndefinedReference;

/**
 * Class ReflectionParameterAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ReflectionParameterAnalyzer implements ParameterAnalyzerInterface
{
    /**
     * {@inheritdoc}
     * @throws ParameterDefinitionAlreadyExistsException
     * @throws ReferenceNotImplementsException
     * @throws MethodDefinitionNotFoundException
     * @throws ParameterDefinitionNotFoundException
     * @throws \InvalidArgumentException
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionParameter $reflectionParameter
    ) {
        $methodName = $reflectionParameter->getDeclaringFunction()->getName();
        $parameterName = $reflectionParameter->getName();
        // Define parameter only if method definition is available
        if ($classDefinition->hasMethod($methodName)) {
            $methodDefinition = $classDefinition->getMethod($methodName);

            // Define parameter definition if not exists
            $parameterDefinition = $methodDefinition->setupParameter($parameterName);
            $this->setReflectionMetadata($parameterDefinition, $reflectionParameter);

            $dependency = $parameterDefinition->getDependency();
            // If dependency was not set
            // UndefinedReference is the default value of dependency which was not use before
            if (!$dependency || ($dependency instanceof UndefinedReference)) {
                $this->setDependencyByReflection($parameterDefinition, $reflectionParameter);
            }

            $this->setOrderArguments($methodDefinition, $reflectionParameter->getDeclaringFunction());
        }
    }

    /**
     * Set correct order of arguments
     *
     * @param MethodDefinition $methodDefinition
     * @param \ReflectionFunctionAbstract $reflectionMethod
     * @throws \InvalidArgumentException
     */
    public function setOrderArguments(
        MethodDefinition $methodDefinition,
        \ReflectionFunctionAbstract $reflectionMethod
    ) {
        $parameters = [];
        // Get parameter names
        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $parameters[] = $reflectionParameter->getName();
        }
        $methodDefinition->setParametersCollectionOrder($parameters);
    }

    /**
     * Set initial reflection metadata into the parameter definition
     *
     * @param ParameterDefinition $parameterDefinition
     * @param \ReflectionParameter $reflectionParameter
     */
    public function setReflectionMetadata(
        ParameterDefinition $parameterDefinition,
        \ReflectionParameter $reflectionParameter
    ) {
        // Set parameter metadata
        if ($reflectionParameter->isDefaultValueAvailable()) {
            $parameterDefinition->setValue($reflectionParameter->getDefaultValue());
        }
        if ($reflectionParameter->getType()) {
            $parameterDefinition->setTypeHint($reflectionParameter->getType());
        }
        $parameterDefinition->setIsOptional($reflectionParameter->isOptional());
    }

    /**
     * Set dependency value by reflection
     *
     * @param ParameterDefinition $parameterDefinition
     * @param \ReflectionParameter $reflectionParameter
     * @throws \InvalidArgumentException
     * @throws ReferenceNotImplementsException
     */
    public function setDependencyByReflection(
        ParameterDefinition $parameterDefinition,
        \ReflectionParameter $reflectionParameter
    ) {
        // If default value available
        if ($reflectionParameter->isDefaultValueAvailable()) {
            // There is a constant
            if ($reflectionParameter->isDefaultValueConstant()) {
                $parameterDefinition->setDependency(
                    new ConstantReference($reflectionParameter->getDefaultValueConstantName())
                );
            } else { // There is some php types
                $parameterDefinition->setDependency(
                    CollectionReference::convertValueToReference($reflectionParameter->getDefaultValue())
                );
            }
            // There is class dependency
        } elseif ($this->checkIfTypeHingIsClass($parameterDefinition)) {
            $class = (string)$parameterDefinition->getTypeHint();
            $parameterDefinition->setDependency(new ClassReference($class));
        }
    }

    /**
     * Check if type hint can be as class reference dependency
     *
     * @param ParameterDefinition $parameterDefinition
     * @return bool
     */
    protected function checkIfTypeHingIsClass(ParameterDefinition $parameterDefinition)
    {
        return is_object($parameterDefinition->getTypeHint()) &&
            (string)$parameterDefinition->getTypeHint() !== '' &&
            !$parameterDefinition->getTypeHint()->isBuiltin();
    }
}
