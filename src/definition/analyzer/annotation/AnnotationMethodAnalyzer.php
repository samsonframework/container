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

/**
 * Class AnnotationMethodAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class AnnotationMethodAnalyzer extends AbstractAnnotationAnalyzer implements MethodAnalyzerInterface
{
    /**
     * {@inheritdoc}
     * @throws MethodDefinitionAlreadyExistsException
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionMethod $reflectionMethod
    ) {
        $methodName = $reflectionMethod->getName();
        // Resolve annotations
        $annotations = $this->reader->getMethodAnnotations($reflectionMethod);
        // Create method definition if annotation is exists
        if (count($annotations)) {
            // Define method if not exists
            if (!$classDefinition->hasMethod($methodName)) {
                $classDefinition->defineMethod($methodName);
            }
            // Exec method annotations
            foreach ($annotations as $annotation) {
                if ($annotation instanceof ResolveMethodInterface) {
                    $annotation->resolveMethod($analyzer, $classDefinition, $reflectionMethod);
                }
            }
        }
    }
}
