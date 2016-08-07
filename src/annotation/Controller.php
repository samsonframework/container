<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\MetadataBuilder;

/**
 * Controller annotation class.
 *
 * This annotation adds class to Controller container scope.
 * @see samsonframework\container\Container::SCOPE_CONTROLLER
 *
 * @Annotation
 */
class Controller implements ClassInterface
{
    /** {@inheritdoc} */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        // Add controller scope to metadata collection
        $classMetadata->scopes[] = MetadataBuilder::SCOPE_CONTROLLER;
    }
}
