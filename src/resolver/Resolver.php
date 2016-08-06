<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:28.
 */

namespace samsonframework\container\resolver;

use samsonframework\container\metadata\ClassMetadata;

abstract class Resolver
{
    /**
     * Convert class reflection to internal metadata class.
     *
     * @param mixed  $classData Class information representative
     * @param string $identifier Unique class container identifier
     *
     * @return ClassMetadata Class metadata
     */
    abstract public function resolve($classData, $identifier = null);
}
