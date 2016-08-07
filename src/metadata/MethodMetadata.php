<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 22:22.
 */
namespace samsonframework\container\metadata;

class MethodMetadata
{
    public $name;

    public $modifiers;

    /**
     * @var \ReflectionParameter[]
     */
    public $parameters = [];

    public $options = [];
}
