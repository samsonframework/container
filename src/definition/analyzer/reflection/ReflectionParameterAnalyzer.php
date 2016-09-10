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
use samsonframework\container\definition\ParameterDefinition;

/**
 * Class ReflectionParameterAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ReflectionParameterAnalyzer implements ParameterAnalyzerInterface
{
    /** {@inheritdoc} */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        \ReflectionParameter $reflectionParameter,
        ClassDefinition $classDefinition,
        ParameterDefinition $parameterDefinition = null
    ) {
        if ($parameterDefinition) {
            // Set parameter metadata
            if ($reflectionParameter->isDefaultValueAvailable()) {
                $parameterDefinition->setValue($reflectionParameter->getDefaultValue());
            }
            $parameterDefinition->setTypeHint($reflectionParameter->getType());
            $parameterDefinition->setIsOptional($reflectionParameter->isOptional());
        }
    }
}
