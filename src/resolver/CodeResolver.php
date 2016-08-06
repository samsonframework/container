<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */

namespace samsonframework\container\resolver;

use samsonframework\di\ClassMetadata;

class CodeResolver extends Resolver
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
