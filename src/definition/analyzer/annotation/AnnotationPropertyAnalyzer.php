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
use samsonframework\container\definition\PropertyDefinition;

class AnnotationPropertyAnalyzer extends AbstractAnnotationAnalyzer implements PropertyAnalyzerInterface
{
    /**
     * {@inheritdoc}
     * @throws PropertyDefinitionAlreadyExistsException
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        \ReflectionProperty $reflectionProperty,
        ClassDefinition $classDefinition,
        PropertyDefinition $propertyDefinition = null
    ) {
        // Resolve annotations
        $annotations = $this->reader->getPropertyAnnotations($reflectionProperty);
        if (count($annotations)) {
            // Define property if not exists
            if (!$propertyDefinition) {
                $propertyDefinition = $classDefinition->defineProperty($reflectionProperty->getName());
            }
            foreach ($annotations as $annotation) {
                if ($annotation instanceof ResolvePropertyInterface) {
                    $annotation->resolveProperty($analyzer, $reflectionProperty, $classDefinition, $propertyDefinition);
                }
            }
        }
    }
}
