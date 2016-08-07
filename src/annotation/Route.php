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
     * @param $path
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($scopeOrScopes)
    {
        parent::__construct($scopeOrScopes);
    }

    /**
     * {@inheritDoc}
     */
    public function toMethodMetadata(MethodMetadata $metadata)
    {
        // TODO: Implement toMetadata() method.
    }

    /**
     * {@inheritDoc}
     */
    public function toClassMetadata(ClassMetadata $metadata)
    {
        // TODO: Implement toClassMetadata() method.
    }
}
