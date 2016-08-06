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
class Scope extends CollectionValue implements MetadataInterface
{
    /** {@inheritdoc} */
    public function toMetadata(ClassMetadata &$metadata)
    {
        // Add all found annotation collection to metadata collection
        $metadata->scopes = array_merge($metadata->scopes, $this->collection);
    }
}
