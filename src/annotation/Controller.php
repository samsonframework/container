<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\Container;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Controller annotation class.
 *
 * This annotation adds class to Controller container scope.
 * @see samsonframework\container\Container::SCOPE_CONTROLLER
 *
 * @Annotation
 */
class Controller implements MetadataInterface
{
    /** {@inheritdoc} */
    public function toMetadata(ClassMetadata &$metadata)
    {
        // Add controller scope to metadata collection
        $metadata->scopes[] = Container::SCOPE_CONTROLLER;
    }
}
