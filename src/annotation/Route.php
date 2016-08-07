<?php
/**
 * Created by PhpStorm.
 * User: Vitaly Iegorov
 * Date: 07.08.2016
 * Time: 11:55.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Class Route.
 *
 * @Annotation
 */
class Route extends CollectionValue implements MethodInterface
{
    /** @var string Route path */
    protected $path;

    /** @var string Route identifier */
    protected $identifier;

    /**
     * Route constructor.
     *
     * @param $path
     */
    public function __construct($scopeOrScopes)
    {
        parent::__construct($scopeOrScopes);

    }

    /**
     * Convert to class metadata.
     *
     * @param ClassMetadata $metadata Input metadata
     *
     * @return ClassMetadata Annotation conversion to metadata
     */
    public function toMethodMetadata(ClassMetadata $metadata)
    {
        // TODO: Implement toMetadata() method.
    }
}
