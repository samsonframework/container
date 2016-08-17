<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection\configurator;

use samsonframework\container\collection\CollectionConfiguratorTrait;
use samsonframework\container\collection\CollectionKeyConfiguratorInterface;
use samsonframework\container\configurator\ScopeConfigurator;

/**
 * Service collection configurator class.
 * @see    \samsonframework\container\configurator\ServiceConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Service extends ScopeConfigurator implements CollectionKeyConfiguratorInterface
{
    use CollectionConfiguratorTrait;
}
