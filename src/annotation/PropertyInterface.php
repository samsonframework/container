<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\annotation;

use samsonframework\container\configurator\PropertyConfiguratorInterface;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Class property annotation interface.
 *
 * @package samsonframework\container\annotation
 */
interface PropertyInterface extends PropertyConfiguratorInterface
{
    /**
     * Convert to class property metadata.
     *
     * @param PropertyMetadata $propertyMetadata Input metadata
     *
     * @return PropertyMetadata Annotation conversion to metadata
     */
    public function toPropertyMetadata(PropertyMetadata $propertyMetadata);
}
