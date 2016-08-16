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
    public function resolve(\ReflectionClass $classReflection, ClassMetadata $classMetadata)
    {
        /** @var \ReflectionClass $classReflection */

        // Create and fill class metadata base fields
        $classMetadata->className = $classReflection->name;
        $classMetadata->nameSpace = $classReflection->getNamespaceName();
        $classMetadata->identifier = $classReflection->name;
        $classMetadata->name = $classMetadata->identifier;

        $this->resolveClassAnnotations($classReflection, $classMetadata);

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
