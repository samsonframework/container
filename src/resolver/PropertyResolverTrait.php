<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 11:25
 */
namespace samsonframework\container\resolver;

use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Class property resolving trait.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
trait PropertyResolverTrait
{
    /**
     * Generic class property resolver.
     *
     * @param \ReflectionProperty $property
     * @param ClassMetadata       $classMetadata
     *
     * @return PropertyMetadata Resolved property metadata
     */
    protected function resolvePropertyMetadata(\ReflectionProperty $property, ClassMetadata $classMetadata) : PropertyMetadata
    {
        // Create method metadata instance
        $propertyMetadata = $classMetadata->methodsMetadata[$property->getName()] ?? new PropertyMetadata($classMetadata);
        $propertyMetadata->name = $property->getName();
        $propertyMetadata->modifiers = $property->getModifiers();
        $propertyMetadata->isPublic = $property->isPublic();
        $propertyMetadata->typeHint = $this->getCommentTypeHint(
            is_string($property->getDocComment()) ? $property->getDocComment() : '');

        // Store property metadata to class metadata
        return $classMetadata->propertiesMetadata[$propertyMetadata->name] = $propertyMetadata;
    }

    /**
     * Parse property comments and return type hint if present.
     *
     * @param string $comments Property comments
     *
     * @return string Property type hint if present
     */
    protected function getCommentTypeHint(string $comments) : string
    {
        // Parse property type hint if present
        if (preg_match('/@var\s+(?<class>[^\s]+)/', $comments, $matches)) {
            return $matches[1];
        }

        return '';
    }
}
