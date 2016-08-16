<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\configurator;

use samsonframework\container\ContainerBuilder;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Controller class configurator.
 *
 * This configurator adds class to Controller container scope.
 * @see    samsonframework\container\Container::SCOPE_CONTROLLER
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class ControllerConfigurator implements ClassConfiguratorInterface
{
    /** {@inheritdoc} */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        // Add controller scope to metadata collection
        $classMetadata->scopes[] = ContainerBuilder::SCOPE_CONTROLLER;
    }
}
