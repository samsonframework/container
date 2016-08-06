<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */

namespace samsonframework\di\resolver;

use samsonframework\di\metadata\ClassMetadata;

class YamlResolver extends Resolver
{
    /**
     * @param \ReflectionClass $class
     *
     * @return ClassMetadata
     *                       TODO Implement it
     */
    public function resolve(\ReflectionClass $class)
    {
        return new ClassMetadata();
    }
}
