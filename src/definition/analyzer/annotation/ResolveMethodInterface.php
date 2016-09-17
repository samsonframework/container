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
interface ResolveMethodInterface
{
    /**
     * Resolve method
     *
     * @param DefinitionAnalyzer $analyzer
     * @param ClassDefinition $classDefinition
     * @param \ReflectionMethod $reflectionMethod
     */
    public function resolveMethod(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionMethod $reflectionMethod
    );
}
