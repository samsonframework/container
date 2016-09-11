<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\reflection;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\ParameterAnalyzerInterface;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\exception\ParameterDefinitionAlreadyExistsException;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\ParameterDefinition;
use samsonframework\container\definition\reference\BoolReference;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\FloatReference;
use samsonframework\container\definition\reference\IntegerReference;
use samsonframework\container\definition\reference\NullReference;
use samsonframework\container\definition\reference\StringReference;
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
            $parameterDefinition->setTypeHint($reflectionParameter->getType());
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
                        $defaultValue = $reflectionParameter->getDefaultValue();
                        if ($defaultValue === null) {
                            $parameterDefinition->setDependency(new NullReference());
                        } elseif (is_string($defaultValue)) {
                            $parameterDefinition->setDependency(new StringReference($defaultValue));
                        } elseif (is_int($defaultValue)) {
                            $parameterDefinition->setDependency(new IntegerReference($defaultValue));
                        } elseif (is_float($defaultValue)) {
                            $parameterDefinition->setDependency(new FloatReference($defaultValue));
                        } elseif (is_bool($defaultValue)) {
                            $parameterDefinition->setDependency(new BoolReference($defaultValue));
                        } elseif (is_array($defaultValue)) {
                            // TODO Convert all items to collection item
                            $parameterDefinition->setDependency(new CollectionReference($defaultValue));
                        }
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
