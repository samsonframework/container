<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\annotation;

use samsonframework\container\configurator\PropertyConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Class properties annotation resolver.
 */
class AnnotationPropertyResolver extends AbstractAnnotationResolver implements AnnotationResolverInterface
{
    /** Property typeHint hint pattern */
    const P_PROPERTY_TYPE_HINT = '/@var\s+(?<class>[^\s]+)/';

    /**
     * {@inheritDoc}
     */
    public function resolve(\ReflectionClass $classReflection, ClassMetadata $classMetadata)
    {
        /** @var \ReflectionProperty $property */
        foreach ($classReflection->getProperties() as $property) {
            $this->resolveClassPropertyAnnotations($property, $classMetadata);
        }

        return $classMetadata;
    }

    /**
     * Resolve class property annotations.
     *
     * @param \ReflectionProperty $property
     * @param ClassMetadata       $classMetadata
     */
    protected function resolveClassPropertyAnnotations(\ReflectionProperty $property, ClassMetadata $classMetadata)
    {
        // Create method metadata instance
        $propertyMetadata = new PropertyMetadata($classMetadata);
        $propertyMetadata->name = $property->getName();
        $propertyMetadata->modifiers = $property->getModifiers();
        $propertyMetadata->isPublic = $property->isPublic();

        // Parse property type hint if present
        if (preg_match(self::P_PROPERTY_TYPE_HINT, $property->getDocComment(), $matches)) {
            list(, $propertyMetadata->typeHint) = $matches;
        }

        /** @var PropertyInterface $annotation Read class annotations */
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof PropertyConfiguratorInterface) {
                $annotation->toPropertyMetadata($propertyMetadata);
            }
        }

        $classMetadata->propertiesMetadata[$propertyMetadata->name] = $propertyMetadata;
    }
}
