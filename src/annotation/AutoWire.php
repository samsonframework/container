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
 * Class AutoWire.
 *
 * @Annotation
 */
class AutoWire implements ClassInterface
{
    /** {@inheritdoc} */
    public function toClassMetadata(ClassMetadata $metadata)
    {
        $metadata->autowire = true;
    }
}
