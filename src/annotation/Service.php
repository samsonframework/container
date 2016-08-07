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
 * Service annotation class.
 *
 * This annotation adds class to Service container scope.
 * @see samsonframework\container\Container::SCOPE_SERVICE
 *
 * @Annotation
 */
class Service extends AnnotationWithValue implements ClassInterface
{
    /** @var string Service unique name */
    protected $name;

    /**
     * Service constructor.
     *
     * @param string $name Service unique name
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($name)
    {
        parent::__construct($name);

        // Get first argument
        $this->name = array_shift($this->collection);
    }

    /** {@inheritdoc} */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->name = $this->name;
        $classMetadata->scopes[] = MetadataBuilder::SCOPE_SERVICES;
    }
}
