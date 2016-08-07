<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.08.16
 * Time: 23:31.
 */

namespace samsonframework\container\scope;

use samsonframework\di\Container;
use samsonframework\di\metadata\ClassMetadata;

class ControllerScope implements Scope
{
    const SCOPE_NAME = 'controller';

    /**
     * @var array
     */
    protected $list;

    /**
     * @param ClassMetadata $metadata
     */
    public function add(ClassMetadata $metadata)
    {
        $this->list[$metadata->identifier] = $metadata;
    }

    /**
     * Build scope.
     *
     * @param Container $container
     */
    public function build(Container $container)
    {
        // Do something
    }

    /**
     * Get name of scope.
     *
     * @return string
     */
    public function getName()
    {
        return self::SCOPE_NAME;
    }

    /**
     * Get list.
     *
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }
}
