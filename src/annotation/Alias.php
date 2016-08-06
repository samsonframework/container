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
class Alias extends CollectionValue
{
    /** {@inheritdoc} */
    public function toMetadata(ClassMetadata &$metadata)
    {
        // Add all found annotation collection to metadata collection
        $metadata->aliases = array_merge($metadata->aliases, $this->collection);
    }
}
