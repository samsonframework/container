<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\collection;

/**
 * Abstract configurator resolver class.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
abstract class AbstractCollectionResolver
{
    /** @var array Collection of collection configurators */
    protected $configurators = [];

    /**
     * AbstractCollectionResolver constructor.
     *
     * @param array $configurators Collection configurators class names
     *
     * @throws \InvalidArgumentException If passed configurator class does not
     * implement CollectionAttributeConfiguratorInterface
     */
    public function __construct(array $configurators)
    {
        /** @var string $configurator */
        foreach ($configurators as $configurator) {
            // Autoload and check if passed collection configurator
            if (in_array(CollectionAttributeConfiguratorInterface::class, class_implements($configurator), true)) {
                $this->configurators[$configurator::getKey($configurator)] = $configurator;
            } else {
                throw new \InvalidArgumentException(
                    $configurator . ' is not valid collection configurator or does not exists'
                );
            }
        }
    }
}
