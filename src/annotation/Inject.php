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
 * Injection annotation class.
 *
 * @Annotation
 */
class Inject extends CollectionValue implements MetadataInterface
{
    /** {@inheritdoc} */
    public function toMetadata(ClassMetadata &$metadata)
    {
        $metadata->dependencies = $this->collection;
    }
}
