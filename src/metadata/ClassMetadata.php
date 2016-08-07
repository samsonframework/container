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
    /** @var string */
    public $className;

    /** @var string */
    public $nameSpace;

    /** @var string */
    public $identifier;

    /** @var bool */
    public $autowire = false;

    /** @var array */
    public $scopes = [];

    /** @var array */
    public $dependencies = [];

    /** @var array */
    public $aliases = [];

    /** @var MethodMetadata[string] */
    public $methodsMetadata = [];

    /** @var PropertyMetadata[string] */
    public $propertiesMetadata = [];
}
