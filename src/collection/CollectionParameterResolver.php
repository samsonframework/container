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
        if (array_key_exists('@attributes', $parameterDataArray)) {
            // Iterate collection attribute configurators
            foreach ($this->collectionConfigurators as $key => $collectionConfigurator) {
                // If this is supported collection configurator
                if (array_key_exists($key, $parameterDataArray['@attributes'])) {
                    /** @var ParameterConfiguratorInterface $configurator Create instance */
                    $configurator = new $collectionConfigurator($parameterDataArray['@attributes'][$key]);
                    // Fill in class metadata
                    $configurator->toParameterMetadata($parameterMetadata);
                }
            }
        }

        return $parameterMetadata;
    }
}
