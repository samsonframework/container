<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 1:59.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Class Alias.
 *
 * @Annotation
 */
class Alias extends CollectionValue implements ClassInterface
{
    /** {@inheritdoc} */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        // Add all found annotation collection to metadata collection
        $classMetadata->aliases = array_merge($classMetadata->aliases, $this->collection);
    }
}
