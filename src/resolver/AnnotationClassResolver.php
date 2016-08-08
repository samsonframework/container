<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\resolver;

use samsonframework\container\annotation\ClassInterface;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Annotation resolver class.
 */
class AnnotationClassResolver extends AbstractAnnotationResolver implements AnnotationResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve(\ReflectionClass $classData, ClassMetadata $classMetadata)
    {
        /** @var \ReflectionClass $classData */

        // Create and fill class metadata base fields
        $classMetadata->className = $classData->name;
        $classMetadata->nameSpace = $classData->getNamespaceName();
        $classMetadata->identifier = $classData->name;
        $classMetadata->name = $classMetadata->identifier;

        $this->resolveClassAnnotations($classData, $classMetadata);

        return $classMetadata;
    }

    /**
     * Resolve all class annotations.
     *
     * @param \ReflectionClass $classData
     * @param ClassMetadata    $metadata
     */
    protected function resolveClassAnnotations(\ReflectionClass $classData, ClassMetadata $metadata)
    {
        /** @var ClassInterface $annotation Read class annotations */
        foreach ($this->reader->getClassAnnotations($classData) as $annotation) {
            if ($annotation instanceof ClassInterface) {
                $annotation->toClassMetadata($metadata);
            }
        }
    }
}
