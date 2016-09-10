<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 17:48
 */
namespace samsonframework\container\definition\analyzer\annotation;

use samsonframework\container\definition\analyzer\ClassAnalyzerInterface;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\ClassDefinition;

class AnnotationClassAnalyzer implements ClassAnalyzerInterface
{
    public function analyze(
        DefinitionAnalyzer $analyzer,
        \ReflectionClass $reflectionClass,
        ClassDefinition $classDefinition
    ) {
        $reader = new AnnotationReader();

    }
}
