<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\collection;

use samsonframework\container\configurator\ClassConfiguratorInterface;
use samsonframework\container\configurator\PropertyConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Abstract configurator resolver class.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
abstract class AbstractCollectionResolver
{
    /** @var array Collection of collection configurators */
    protected $collectionConfigurators = [];

    /**
     * ArrayPropertyResolver constructor.
     *
     * @param array $collectionConfigurators
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $collectionConfigurators)
    {
        /** @var string $collectionConfigurator */
        foreach ($collectionConfigurators as $collectionConfigurator) {
            // Autoload and check if passed collection configurator
            if (in_array(CollectionAttributeConfiguratorInterface::class, class_implements($collectionConfigurator), true)) {
                $this->collectionConfigurators[$this->getKey($collectionConfigurator)] = $collectionConfigurator;
            } else {
                throw new \InvalidArgumentException($collectionConfigurator . ' is not valid collection configurator or does not exists');
            }
        }
    }

    /**
     * Get collection configurator collection key name for resolving.
     *
     * @param string $className Full collection configurator class name with namespace
     *
     * @return string Collection configurator collection key name
     */
    public function getKey($className) : string
    {
        $reflection = new \ReflectionClass($className);
        if ($key = $reflection->getConstant('CONFIGURATOR_KEY')) {
            return $key;
        }

        // Get collection configurator key as its lowered class name
        return strtolower(substr($className, strrpos($className, '\\') + 1));
    }
}
