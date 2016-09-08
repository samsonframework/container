<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition\analyzer;

use samsonframework\container\definition\DefinitionAnalyzer;

/**
 * Interface PropertyAnalyzerInterface
 * 
 * @package samsonframework\container\definition
 */
interface PropertyAnalyzerInterface
{
    /**
     * Analyze property definition
     * 
     * @param DefinitionAnalyzer $analyzer
     * @param \ReflectionProperty $reflectionProperty
     * @return mixed
     */
    public function analyze(DefinitionAnalyzer $analyzer, \ReflectionProperty $reflectionProperty);
}

