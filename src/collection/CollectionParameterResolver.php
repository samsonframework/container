<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\collection;

use samsonframework\container\configurator\ParameterConfiguratorInterface;
use samsonframework\container\metadata\ParameterMetadata;

/**
 * Collection parameter resolver class.
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class CollectionParameterResolver extends AbstractCollectionResolver implements CollectionParameterResolverInterface
{
    /** Collection parameter key */
    const KEY = 'arguments';

    /**
     * {@inheritDoc}
     */
    public function resolve(array $parameterDataArray, ParameterMetadata $parameterMetadata)
    {
        // Iterate collection
        foreach ($this->getAttributeConfigurator($parameterDataArray) as $configurator) {
            /** @var ParameterConfiguratorInterface $configurator Parse parameter metadata */
            $configurator->toParameterMetadata($parameterMetadata);
        }

        return $parameterMetadata;
    }
}
