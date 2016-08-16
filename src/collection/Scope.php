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
 * Scope collection configurator class.
 * @see    \samsonframework\container\configurator\ScopeConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Scope extends ScopeConfigurator implements CollectionConfiguratorInterface
{
    /**
     * Scope annotation configurator constructor.
     *
     * @param string|array $valueOrValues Service unique name
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($valueOrValues)
    {
        // Parse annotation value
        $scopeNameData = $this->parseAnnotationValue($valueOrValues);

        // Pass to scope configurator
        parent::__construct(array_shift($scopeNameData));
    }
}
