<?php declare(strict_types=1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 17:48
 */
namespace samsonframework\container\definition\analyzer\annotation;

use samsonframework\container\definition\analyzer\ClassAnalyzerInterface;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\ClassDefinition;

/**
 * Class AnnotationClassAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class AnnotationClassAnalyzer extends AbstractAnnotationAnalyzer implements ClassAnalyzerInterface
{
    /**
     * Analyze class
     *
     * @param DefinitionAnalyzer $analyzer
     * @param \ReflectionClass $reflectionClass
     * @param ClassDefinition $classDefinition
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionClass $reflectionClass
    ) {
        $annotations = $this->reader->getClassAnnotations($reflectionClass);
        // Exec class annotations
        foreach ($annotations as $annotation) {
            if ($annotation instanceof ResolveClassInterface) {
                $annotation->resolveClass($analyzer, $classDefinition, $reflectionClass);
            }
        }
    }
}
