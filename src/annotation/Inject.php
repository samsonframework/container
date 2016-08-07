<?php
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

        // Check for inheritance violation
        if ($propertyMetadata->injectable !== null && $propertyMetadata->typeHint !== '') {
            $inheritance = array_merge([$propertyMetadata->injectable], class_parents($propertyMetadata->injectable));
            if (!in_array($propertyMetadata->typeHint, $inheritance, true)) {
                throw new \InvalidArgumentException('@Inject dependency violates ' . $propertyMetadata->typeHint . ' inheritance');
            }
        }
    }
}
