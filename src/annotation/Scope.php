<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Class Scope.
 *
 * @Annotation
 */
class Scope extends CollectionValue implements ClassInterface
{
    /** {@inheritdoc} */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        // Add all found annotation collection to metadata collection
        $classMetadata->scopes = array_merge($classMetadata->scopes, $this->collection);
    }
}
