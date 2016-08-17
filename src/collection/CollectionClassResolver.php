<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\collection;

use samsonframework\container\configurator\ClassConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Collection class resolver class.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class CollectionClassResolver extends AbstractCollectionResolver implements CollectionResolverInterface
{
    /** Collection class key */
    const KEY = 'instance';

    /**
     * {@inheritDoc}
     */
    public function resolve(array $classDataArray, ClassMetadata $classMetadata)
    {
        // Iterate collection
        if (array_key_exists('@attributes', $classDataArray)) {
            // Iterate collection attribute configurators
            foreach ($this->configurators as $key => $collectionConfigurator) {
                // If this is supported collection configurator
                if (array_key_exists($key, $classDataArray['@attributes'])) {
                    /** @var ClassConfiguratorInterface $configurator Create instance */
                    $configurator = new $collectionConfigurator($classDataArray['@attributes'][$key]);
                    // Fill in class metadata
                    $configurator->toClassMetadata($classMetadata);
                }
            }
        }

        return $classMetadata;
    }
}
