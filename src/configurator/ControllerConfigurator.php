<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\configurator;

use samsonframework\container\ContainerBuilder;

/**
 * Controller class configurator.
 *
 * This configurator adds class to Controller container scope.
 * @see    samsonframework\container\Container::SCOPE_CONTROLLER
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class ControllerConfigurator extends ScopeConfigurator
{
    /**
     * ControllerConfigurator constructor.
     */
    public function __construct()
    {
        // Add to controller scope
        parent::__construct(ContainerBuilder::SCOPE_CONTROLLER);
    }
}
