<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 17:48
 */
namespace samsonframework\container\definition\analyzer\annotation;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\PropertyDefinition;

/**
 * Interface ResolvePropertyInterface
 *
 * @package samsonframework\container\definition\analyzer\annotation
 */
interface ResolvePropertyInterface
{
    /**
     * Resolve property
     *
     * @param DefinitionAnalyzer $analyzer
     * @param \ReflectionProperty $reflectionProperty
     * @param ClassDefinition $classDefinition
     * @param PropertyDefinition $propertyDefinition
     */
    public function resolveProperty(
        DefinitionAnalyzer $analyzer,
        \ReflectionProperty $reflectionProperty,
        ClassDefinition $classDefinition,
        PropertyDefinition $propertyDefinition
    );
}
