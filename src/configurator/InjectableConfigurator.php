<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 14.08.16 at 20:33
 */
namespace samsonframework\container\configurator;

use samsonframework\container\metadata\PropertyMetadata;

/**
 * Property injection configurator.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class InjectableConfigurator implements PropertyConfiguratorInterface
{
    /**
     * Convert to class property metadata.
     *
     * @param PropertyMetadata $propertyMetadata Input metadata
     *
     * @return PropertyMetadata Annotation conversion to metadata
     */
    public function toPropertyMetadata(PropertyMetadata $propertyMetadata)
    {
        // Checks?
    }
}
