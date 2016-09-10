<?php declare(strict_types=1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 17:48
 */
namespace samsonframework\container\definition\analyzer\annotation;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\MethodAnalyzerInterface;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\definition\MethodDefinition;

class AnnotationMethodAnalyzer extends AbstractAnnotationAnalyzer implements MethodAnalyzerInterface
{
    /**
     * {@inheritdoc}
     * @throws MethodDefinitionAlreadyExistsException
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        \ReflectionMethod $reflectionMethod,
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition = null
    ) {
        // Define property if not exists
        if (!$methodDefinition) {
            $methodDefinition = $classDefinition->defineMethod($reflectionMethod->getName());
        }
        // Resolve annotations
        $annotations = $this->reader->getMethodAnnotations($reflectionMethod);
        foreach ($annotations as $annotation) {
            if ($annotation instanceof ResolveMethodInterface) {
                $annotation->resolveMethod($analyzer, $reflectionMethod, $classDefinition, $methodDefinition);
            }
        }
    }
}
