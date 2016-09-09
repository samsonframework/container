<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition\analyzer;

use samsonframework\container\definition\DefinitionAnalyzer;

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
     * @param \ReflectionParameter $reflectionParameter
     */
    public function analyze(\ReflectionParameter $reflectionParameter);
}

