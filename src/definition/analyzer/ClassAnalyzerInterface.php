<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition\analyzer;

use samsonframework\container\definition\DefinitionAnalyzer;

/**
 * Interface ClassAnalyzerInterface
 *
 * @package samsonframework\container\definition
 */
interface ClassAnalyzerInterface
{
    /**
     * Analyze class definition
     *
     * @param \ReflectionClass $reflectionClass
     * @return mixed
     */
    public function analyze(\ReflectionClass $reflectionClass);
}
