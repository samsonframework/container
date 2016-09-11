<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition\analyzer;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\ParameterDefinition;

/**
 * Interface ParameterAnalyzerInterface
 *
 * @package samsonframework\container\definition
 */
interface ParameterAnalyzerInterface
{
    /**
     * Analyze parameter definition
     *
     * @param DefinitionAnalyzer $analyzer
     * @param \ReflectionParameter $reflectionParameter
     * @param ClassDefinition $classDefinition
     * @param MethodDefinition $methodDefinition
     * @param ParameterDefinition $parameterDefinition
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        \ReflectionParameter $reflectionParameter,
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition = null,
        ParameterDefinition $parameterDefinition = null
    );
}

