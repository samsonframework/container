<?php declare(strict_types=1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 17:48
 */
namespace samsonframework\container\definition\analyzer\annotation;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\PropertyAnalyzerInterface;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\exception\PropertyDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\PropertyDefinitionNotFoundException;

/**
 * Class AnnotationPropertyAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class AnnotationPropertyAnalyzer extends AbstractAnnotationAnalyzer implements PropertyAnalyzerInterface
{
    /**
     * {@inheritdoc}
     * @throws PropertyDefinitionAlreadyExistsException
     * @throws PropertyDefinitionNotFoundException
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionProperty $reflectionProperty
    ) {
        $propertyName = $reflectionProperty->getName();
        // Resolve annotations
        $annotations = $this->reader->getPropertyAnnotations($reflectionProperty);
        if (count($annotations)) {
            // Define property if not exists
            if (!$classDefinition->hasProperty($propertyName)) {
                $classDefinition->defineProperty($propertyName);
            }
            // Exec annotations
            foreach ($annotations as $annotation) {
                if ($annotation instanceof ResolvePropertyInterface) {
                    $annotation->resolveProperty($analyzer, $classDefinition, $reflectionProperty);
                }
            }
        }
    }
}
