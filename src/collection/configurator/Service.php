<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection\configurator;

use samsonframework\container\collection\CollectionKeyConfiguratorInterface;
use samsonframework\container\configurator\ServiceConfigurator;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Service collection configurator class.
 * @see    \samsonframework\container\configurator\ServiceConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Service extends ServiceConfigurator implements CollectionKeyConfiguratorInterface
{
    use CollectionConfiguratorTrait;

    public function __construct(array $scopeData)
    {
        // Check for name attribute
        if (array_key_exists('@attributes', $scopeData) && array_key_exists('name', $scopeData['@attributes'])) {
            parent::__construct($scopeData['@attributes']['name']);
        } else {
            throw new \InvalidArgumentException('Cannot configure service without name attribute');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($data)
    {
        $classMetadata = new ClassMetadata();
        $classMetadata->name = $this->serviceName;
        $classMetadata->scopes[] = $this->scopeName;

        return $classMetadata;
    }
}
