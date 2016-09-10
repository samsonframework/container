<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition\analyzer;

use samsonframework\container\definition\analyzer\exception\ParameterNotFoundException;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\MethodDefinition;

/**
 * Interface PropertyAnalyzerInterface
 * 
 * @package samsonframework\container\definition
 */
interface MethodAnalyzerInterface
{
    /**
     * Analyze property definition
     *
     * @param DefinitionAnalyzer $analyzer
     * @param \ReflectionMethod $reflectionMethod
     * @param ClassDefinition $classDefinition
     * @param MethodDefinition $methodDefinition
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        \ReflectionMethod $reflectionMethod,
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition = null
    );
}

