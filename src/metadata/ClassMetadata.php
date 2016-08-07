<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 22:22.
 */
namespace samsonframework\container\metadata;

/**
 * Generic class metadata entity.
 *
 * @package samsonframework\container\metadata
 */
class ClassMetadata extends AbstractMetadata
{
    /** @var string Full class name with namesace */
    public $className;

    /** @var string Class name space */
    public $nameSpace;

    /** @var string Unique class identifier, what for? Class is unique */
    public $identifier;

    /** @var bool Do we need it? */
    public $autowire = false;

    /** @var array Class container scopes */
    public $scopes = [];

    /** @var string Class alias? */
    public $alias;

    /** @var string[string] Class routes collection */
    public $routes;

    /** @var MethodMetadata[] Collection of class methods metadata */
    public $methodsMetadata = [];

    /** @var PropertyMetadata[] Collection of class properties metadata */
    public $propertiesMetadata = [];
}
