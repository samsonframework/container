<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 17:48
 */
namespace samsonframework\container\definition\analyzer\annotation;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\MethodDefinition;

/**
 * Interface ResolvePropertyInterface
 *
 * @package samsonframework\container\definition\analyzer\annotation
 */
interface ResolveClassInterface
{
    /**
     * Resolve class
     *
     * @param DefinitionAnalyzer $analyzer
     * @param ClassDefinition $classDefinition
     * @param \ReflectionClass $reflectionClass
     */
    public function resolveClass(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionClass $reflectionClass
    );
}
