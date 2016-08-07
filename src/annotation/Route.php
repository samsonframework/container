<?php
/**
 * Created by PhpStorm.
 * User: Vitaly Iegorov
 * Date: 07.08.2016
 * Time: 11:55.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;

/**
 * Class Route.
 *
 * @Annotation
 */
class Route extends CollectionValue implements ClassInterface, MethodInterface
{
    /** @var string Route path */
    protected $path;

    /** @var string Route identifier */
    protected $identifier;

    /**
     * Route constructor.
     *
     * @param array $valueOrValues
     */
    public function __construct($valueOrValues)
    {
        parent::__construct($valueOrValues);

        // Set data
        $this->path = $valueOrValues['value'] ?? null;
        $this->identifier = $valueOrValues['name'] ?? uniqid(__CLASS__, true);
    }

    /**
     * {@inheritDoc}
     */
    public function toMethodMetadata(MethodMetadata $methodMetadata)
    {
        $methodMetadata->routes[$this->identifier] = $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->routes[$this->identifier] = $this->path;
    }
}
