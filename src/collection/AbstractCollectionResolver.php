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
                $this->configurators[$this->getKey($collectionConfigurator)] = $collectionConfigurator;
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

    /**
     * Try to find attribute configurators.
     *
     * @param array $arrayData Configuration data array
     *
     * @return CollectionAttributeConfiguratorInterface[] Found attribute configurator instances collection
     */
    public function getAttributeConfigurator(array $arrayData) : array
    {
        $configurators = [];

        // If we have @attributes section
        if (array_key_exists('@attributes', $arrayData)) {
            // Iterate collection attribute configurators
            foreach ($this->configurators as $key => $configurator) {
                // If this is supported collection configurator
                if (array_key_exists($key, $arrayData['@attributes'])) {
                    // Store new attribute configurator instance
                    $configurators[$key] = new $configurator($arrayData['@attributes'][$key]);
                }
            }
        }

        return $configurators;
    }
}
