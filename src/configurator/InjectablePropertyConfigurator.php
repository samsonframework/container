<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 14.08.16 at 20:33
 */
namespace samsonframework\container\configurator;

use samsonframework\container\configurator\expection\ClassDoesNotExists;
use samsonframework\container\configurator\expection\TypeHintDoesNotExists;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Property injection configurator.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class InjectablePropertyConfigurator extends InjectableAbstractConfigurator implements PropertyConfiguratorInterface
{
    /**
     * Convert to class property metadata.
     *
     * @param PropertyMetadata $propertyMetadata Input metadata
     *
     * @return PropertyMetadata Annotation conversion to metadata
     *
     * @throws TypeHintDoesNotExists
     * @throws ClassDoesNotExists
     *
     */
    public function toPropertyMetadata(PropertyMetadata $propertyMetadata)
    {
        // Check if there is no type hint - we cannot inject without it
        if ($this->argumentType === null || $this->argumentType === '') {
            throw new TypeHintDoesNotExists('Cannot configure property "' . $propertyMetadata->name . '" injection');
        }

        // Check if specified type hint exists
        if (!class_exists($this->argumentType)) {
            throw new ClassDoesNotExists('Cannot configure property "' . $this->argumentName . '" with "' . $this->argumentType . '"');
        }

        // Store property metadata
        $propertyMetadata->typeHint = $this->argumentType;
        $propertyMetadata->name = $this->argumentName;
    }
}
