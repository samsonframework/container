<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.08.16
 * Time: 23:31.
 */

namespace samsonframework\di\scope;

use samsonframework\di\Container;
use samsonframework\di\metadata\ClassMetadata;

interface Scope
{
    /**
     * @param ClassMetadata $metadata
     */
    public function add(ClassMetadata $metadata);

    public function getName();
    public function build(Container $container);
    public function getList();
}
