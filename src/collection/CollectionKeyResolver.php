<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 17.08.16 at 10:14
 */
namespace samsonframework\container\collection;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Collection key resolver.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class CollectionKeyResolver extends AbstractCollectionResolver
{
    /** @var  CollectionAttributeResolver */
    protected $attributeResolver;

    /**
     * CollectionKeyResolver constructor.
     *
     * @param array                       $configurators
     *
     * @param CollectionAttributeResolver $attributeResolver
     */
    public function __construct(array $configurators, CollectionAttributeResolver $attributeResolver)
    {
        $this->attributeResolver = $attributeResolver;

        // Fill with key nested interface
        $this->configurators = $this->getConfiguratorsByInterface(
            $configurators,
            CollectionKeyConfiguratorInterface::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function resolveKey(string $key, array $data, ClassMetadata $classMetadata = null)
    {
        $metadata = null;

        // If this is supported collection key configurator
        if (array_key_exists($key, $this->configurators)) {
            // Create configurator and retrieve metadata
            $metadata = (new $this->configurators[$key]($data))->resolve($data, $classMetadata);

            // If we have parsed metadata
            if ($metadata !== null) {
                // And we have attributes for this key
                if (array_key_exists('@attributes', $data)) {
                    // Resolve attributes
                    foreach ($data['@attributes'] as $attributeKey => $attributeValue) {
                        $this->attributeResolver->resolveAttribute($attributeKey, $attributeValue, $metadata);
                    }
                }
            }
        }

        return $metadata;
    }
}
