<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection\attribute;

use samsonframework\container\configurator\ScopeConfigurator;

/**
 * Scope collection configurator class.
 *
 * @see    \samsonframework\container\configurator\ScopeConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Scope extends ScopeConfigurator implements AttributeConfiguratorInterface
{
    /** @var string Configurator key */
    const KEY = 'scope';
}
