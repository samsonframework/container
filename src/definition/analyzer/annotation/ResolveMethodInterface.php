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
     * @param \ReflectionMethod $reflectionMethod
     * @param ClassDefinition $classDefinition
     * @param MethodDefinition $methodDefinition
     */
    public function resolveMethod(
        DefinitionAnalyzer $analyzer,
        \ReflectionMethod $reflectionMethod,
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition
    );
}
