<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection;

use samsonframework\container\configurator\ScopeConfigurator;

/**
 * Service collection configurator class.
 *
 * @see    \samsonframework\container\configurator\ServiceConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Service extends ScopeConfigurator implements CollectionKeyConfiguratorInterface
{
    /**
     * Service collection configurator constructor.
     *
     * @param string|array $valueOrValues Service unique name
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($valueOrValues)
    {
        // Pass to scope configurator
        parent::__construct($valueOrValues);
    }
}
