<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection\attribute;

use samsonframework\container\configurator\ParameterConfiguratorInterface;
use samsonframework\container\configurator\ServiceConfigurator;
use samsonframework\container\metadata\ParameterMetadata;

/**
 * Service attribute configurator class.
 *
 * @see    \samsonframework\container\configurator\ScopeConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Service extends ServiceConfigurator implements AttributeConfiguratorInterface, ParameterConfiguratorInterface
{
    /** @var string Configurator key */
    const KEY = 'service';

    /**
     * {@inheritdoc}
     */
    public function toParameterMetadata(ParameterMetadata $parameterMetadata)
    {
        $parameterMetadata->dependency = $this->serviceName;
    }
}
