<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\collection;

use samsonframework\container\collection\configurator\CollectionConfiguratorTrait;

/**
 * Abstract configurator resolver class.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
abstract class AbstractCollectionResolver
{
    /** @var array Collection of attribute configurators attribute => class names */
    protected $configurators = [];

    /**
     * Validate passed configurator classes that they implement specific interface and
     * use CollectionConfiguratorTrait.
     *
     * After them in collection by keys retrieved from CollectionConfiguratorTrait::getKey().
     *
     * @param array  $configurators Collection of configurator classes
     * @param string $interface     Implemented interface filter
     *
     * @return array Collection of marker => collection configurator class name
     *
     * @throws \InvalidArgumentException
     */
    public function getConfiguratorsByInterface(array $configurators, string $interface) : array
    {
        $result = [];
        /** @var string $configurator */
        foreach ($configurators as $configurator) {
            // Autoload and check if passed class implements needed interface
            if (in_array($interface, class_implements($configurator), true)
                && in_array(CollectionConfiguratorTrait::class, class_uses($configurator), true)
            ) {
                $result[$configurator::getMarker($configurator)] = $configurator;
            } else {
                throw new \InvalidArgumentException(
                    $configurator . ' is not valid collection configurator or does not exists'
                );
            }
        }

        return $result;
    }
}
