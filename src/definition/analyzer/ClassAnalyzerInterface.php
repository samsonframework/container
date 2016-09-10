<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition\analyzer;

use samsonframework\container\definition\ClassDefinition;

/**
 * Interface ClassAnalyzerInterface
 *
 * @package samsonframework\container\definition\analyzer
 */
interface ClassAnalyzerInterface
{
    /**
     * Analyze class definition
     *
     * @param DefinitionAnalyzer $analyzer
     * @param \ReflectionClass $reflectionClass
     * @param ClassDefinition $classDefinition
     */
    public function analyze(DefinitionAnalyzer $analyzer, \ReflectionClass $reflectionClass, ClassDefinition $classDefinition);
}
