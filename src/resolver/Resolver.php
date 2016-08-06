<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:28.
 */

namespace samsonframework\container\resolver;

use samsonframework\di\ClassMetadata;

abstract class Resolver
{
    /** @var string[] Collection of paths for scanning  */
    protected $paths = [];

    /**
     * @param \ReflectionClass $class
     *
     * @return ClassMetadata
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
