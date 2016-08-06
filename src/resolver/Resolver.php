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
     * @param \ReflectionClass $class
     * @see ClassMetadata
     * @return ClassMetadata Class metadata
     */
    abstract public function resolve(\ReflectionClass $class);

    /**
     * Get internal id for container item.
     *
     * @return string
     */
    protected function createInternalId()
    {
        return uniqid('container_di', true);
    }
}
