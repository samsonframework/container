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
        foreach ($this->collection as $name => $serviceName) {
            $arg = ['service' => $serviceName];
            if (is_string($name)) {
                $metadata->args[$name] = $arg;
            } else {
                $metadata->args[] = $arg;
            }
        }
    }
}
