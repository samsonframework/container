<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection\configurator;

use samsonframework\container\collection\CollectionAttributeConfiguratorInterface;
use samsonframework\container\configurator\ScopeConfigurator;

/**
 * Scope collection configurator class.
 *
 * @see    \samsonframework\container\configurator\ScopeConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Scope extends ScopeConfigurator implements CollectionAttributeConfiguratorInterface
{
    use CollectionConfiguratorTrait;
}
