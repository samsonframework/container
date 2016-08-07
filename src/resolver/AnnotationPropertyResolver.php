<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\resolver;

use samsonframework\container\annotation\PropertyInterface;
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
    public function resolve(\ReflectionClass $classData, ClassMetadata $classMetadata)
    {
        /** @var \ReflectionProperty $property */
        foreach ($classData->getProperties() as $property) {
            $this->resolveClassPropertyAnnotations($property, $this->classMetadata);
        }

        return $this->classMetadata;
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

        // Parse property type hint if present
        if (preg_match('/@var\s+(?<class>[^\s]+)/', $property->getDocComment(), $matches)) {
            list(, $propertyMetadata->typeHint) = $matches;
        }

        /** @var PropertyInterface $annotation Read class annotations */
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof PropertyInterface) {
                $annotation->toPropertyMetadata($propertyMetadata);
            }
        }

        $classMetadata->propertyMetadata[$propertyMetadata->name] = $propertyMetadata;
    }
}
