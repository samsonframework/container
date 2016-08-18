<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\annotation;

use samsonframework\container\configurator\PropertyConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\PropertyMetadata;
use samsonframework\container\resolver\PropertyResolverTrait;

/**
 * Class properties annotation resolver.
 */
class AnnotationPropertyResolver extends AbstractAnnotationResolver implements AnnotationResolverInterface
{
    use PropertyResolverTrait;

    /**
     * {@inheritDoc}
     */
    public function resolve(\ReflectionClass $classReflection, ClassMetadata $classMetadata)
    {
        /** @var \ReflectionProperty $property */
        foreach ($classReflection->getProperties() as $property) {
            $this->resolveClassPropertyAnnotations(
                $property,
                $this->resolvePropertyMetadata($property, $classMetadata)
            );
        }

        return $classMetadata;
    }

    /**
     * Resolve class property annotations.
     *
     * @param \ReflectionProperty $property
     * @param PropertyMetadata    $propertyMetadata
     */
    protected function resolveClassPropertyAnnotations(\ReflectionProperty $property, PropertyMetadata $propertyMetadata)
    {
        /** @var PropertyConfiguratorInterface $annotation Read class annotations */
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof PropertyConfiguratorInterface) {
                $annotation->toPropertyMetadata($propertyMetadata);
            }
        }
    }
}
