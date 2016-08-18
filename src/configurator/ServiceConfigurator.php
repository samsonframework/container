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
 * Service class configurator.
 *
 * This configurator adds class to container service scope.
 * @see    samsonframework\container\Container::SCOPE_SERVICE
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class ServiceConfigurator extends ScopeConfigurator
{
    /** @var string Class service name */
    protected $serviceName;

    /**
     * ServiceConfigurator constructor.
     *
     * @param string $serviceData Class service name
     */
    public function __construct(string $serviceData)
    {
        $this->serviceName = $serviceData;

        // Add to service scopes
        parent::__construct(ContainerBuilder::SCOPE_SERVICES);
    }

    /** {@inheritdoc} */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        parent::toClassMetadata($classMetadata);

        // Add service name
        $classMetadata->name = $this->serviceName;
    }
}
