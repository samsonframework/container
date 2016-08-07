<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Injection annotation class.
 *
 * @Annotation
 */
class Inject extends CollectionValue implements MethodInterface, PropertyInterface
{
    /** {@inheritdoc} */
    public function toMethodMetadata(MethodMetadata $metadata)
    {
        $metadata->dependencies = $this->collection;
    }

    /** {@inheritdoc} */
    public function toPropertyMetadata(PropertyMetadata $propertyMetadata)
    {
        // Get @Inject("value")
        $propertyMetadata->injectable = array_shift($this->collection);

        // Check if we need to append namespace to injectable
        if ($propertyMetadata->injectable !== null && strpos($propertyMetadata->injectable, '\\') === false) {
            $propertyMetadata->injectable = $propertyMetadata->classMetadata->nameSpace . '\\' . $propertyMetadata->injectable;
        }

        // Check if we need to append namespace to type hint
        if ($propertyMetadata->typeHint !== null && strpos($propertyMetadata->typeHint, '\\') === false) {
            $propertyMetadata->typeHint = $propertyMetadata->classMetadata->nameSpace . '\\' . $propertyMetadata->typeHint;
        }

        // Check for inheritance violation
        if ($this->checkInheritanceViolation($propertyMetadata)) {
            throw new \InvalidArgumentException('@Inject dependency violates ' . $propertyMetadata->typeHint . ' inheritance');
        }

        if ($this->checkInterfaceWithoutClassName($propertyMetadata)) {
            throw new \InvalidArgumentException('Cannot @Inject interface, inherited class name should be specified');
        }
    }

    /**
     * Check if @Inject violates inheritance.
     *
     * @param PropertyMetadata $propertyMetadata
     *
     * @return bool True if @Inject violates inheritance
     */
    protected function checkInheritanceViolation(PropertyMetadata $propertyMetadata) : bool
    {
        // Check for inheritance violation
        if ($propertyMetadata->injectable !== null && $propertyMetadata->typeHint !== null) {
            $inheritance = array_merge([$propertyMetadata->injectable], class_parents($propertyMetadata->injectable));
            return !in_array($propertyMetadata->typeHint, $inheritance, true);
        }

        return false;
    }

    /**
     * Check if @Inject has no class name and type hint is interface.
     *
     * @param PropertyMetadata $propertyMetadata
     *
     * @return bool True if @Inject has no class name and type hint is interface.
     */
    protected function checkInterfaceWithoutClassName(PropertyMetadata $propertyMetadata) : bool
    {
        return $propertyMetadata->typeHint !== null
        && $propertyMetadata->injectable === null
        && (new \ReflectionClass($propertyMetadata->typeHint))->isInterface();
    }
}
