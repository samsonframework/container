<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 17.08.16 at 10:14
 */
namespace samsonframework\container\collection;

use samsonframework\container\configurator\ClassConfiguratorInterface;
use samsonframework\container\configurator\MethodConfiguratorInterface;
use samsonframework\container\configurator\PropertyConfiguratorInterface;
use samsonframework\container\metadata\AbstractMetadata;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Collection attribute resolver.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class CollectionAttributeResolver extends AbstractCollectionResolver
{
    /**
     * CollectionAttributeResolver constructor.
     *
     * @param array $configurators
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $configurators)
    {
        // Fill with key nested interface
        $this->configurators = $this->getConfiguratorsByInterface(
            $configurators,
            CollectionAttributeConfiguratorInterface::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function resolveAttribute(string $key, string $value, AbstractMetadata $metadata)
    {
        // If this is supported collection configurator
        if (array_key_exists($key, $this->configurators)) {
            /** @var CollectionAttributeConfiguratorInterface $configurator Create configurator */
            $configurator = new $this->configurators[$key]($value);
            if ($configurator instanceof ClassConfiguratorInterface && $metadata instanceof ClassMetadata) {
                $configurator->toClassMetadata($metadata);
            }
            if ($configurator instanceof PropertyConfiguratorInterface && $metadata instanceof PropertyMetadata) {
                $configurator->toPropertyMetadata($metadata);
            }
            if ($configurator instanceof MethodConfiguratorInterface && $metadata instanceof MethodMetadata) {
                $configurator->toMethodMetadata($metadata);
            }
        }

        return $metadata;
    }
}
