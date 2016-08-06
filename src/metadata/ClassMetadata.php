<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 22:22.
 */

namespace samsonframework\di\metadata;

class ClassMetadata
{
    public $name;
    public $className;
    public $internalId;
    public $autowire;
    public $scopes = [];
    public $args = [];
    public $aliases = [];

    /**
     * @var MethodMetadata[]
     */
    public $methodsMetadata = [];
}
