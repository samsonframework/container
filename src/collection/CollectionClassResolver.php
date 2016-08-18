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
    public function resolve(array $configurationArray, ClassMetadata $classMetadata)
    {
        // Iterate collection
        foreach ($this->getAttributeConfigurator($configurationArray) as $configurator) {
            /** @var ClassConfiguratorInterface $configurator Parse class metadata */
            $configurator->toClassMetadata($classMetadata);
        }

        return $classMetadata;
    }
}
