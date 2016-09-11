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
use samsonframework\container\definition\exception\ParameterDefinitionAlreadyExistsException;
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
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        \ReflectionParameter $reflectionParameter,
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition = null,
        ParameterDefinition $parameterDefinition = null
    ) {
        // Define parameter only if method definition is available
        if ($methodDefinition) {
            // Define parameter definition if not exists
            if (!$parameterDefinition) {
                $parameterDefinition = $methodDefinition->defineParameter($reflectionParameter->getName());
            }
            // Set parameter metadata
            if ($reflectionParameter->isDefaultValueAvailable()) {
                $parameterDefinition->setValue($reflectionParameter->getDefaultValue());
            }
            if ($reflectionParameter->getType()) {
                $parameterDefinition->setTypeHint($reflectionParameter->getType());
            }
            $parameterDefinition->setIsOptional($reflectionParameter->isOptional());

            $dependency = $parameterDefinition->getDependency();
            // If dependency was not set
            if (!$dependency || ($dependency instanceof UndefinedReference)) {
                // If default value available
                if ($reflectionParameter->isDefaultValueAvailable()) {
                    // There is a constant
                    if ($reflectionParameter->isDefaultValueConstant()) {
                        $parameterDefinition->setDependency(
                            new ConstantReference($reflectionParameter->getDefaultValueConstantName())
                        );
                        // There is some php types
                    } else {
                        $parameterDefinition->setDependency(
                            CollectionReference::convertValueToReference($reflectionParameter->getDefaultValue())
                        );
                    }
                    // There is class dependency
                } elseif (
                    is_object($parameterDefinition->getTypeHint()) &&
                    (string)$parameterDefinition->getTypeHint() !== '' &&
                    !$parameterDefinition->getTypeHint()->isBuiltin()
                ) {
                    $parameterDefinition->setDependency(
                        new ClassReference((string)$parameterDefinition->getTypeHint())
                    );
                }
            }
        }
    }
}
