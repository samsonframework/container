<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\collection;

use samsonframework\container\annotation\AnnotationPropertyResolver;
use samsonframework\container\configurator\PropertyConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Collection property resolver class.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class CollectionPropertyResolver extends AbstractCollectionResolver implements CollectionResolverInterface
{
    /** Collection property key */
    const KEY = 'properties';

    /**
     * {@inheritDoc}
     */
    public function resolve(array $configurationArray, ClassMetadata $classMetadata)
    {
        // Iterate collection
        if (array_key_exists(self::KEY, $configurationArray)) {
            $reflectionClass = new \ReflectionClass($classMetadata->className);
            // Iterate configured properties
            foreach ($configurationArray[self::KEY] as $propertyName => $propertyDataArray) {
                $propertyReflection = $reflectionClass->getProperty($propertyName);

                // Create method metadata instance
                // TODO This code are identical with AnnotationPropertyResolver
                $propertyMetadata = new PropertyMetadata($classMetadata);
                $propertyMetadata->name = $propertyReflection->getName();
                $propertyMetadata->modifiers = $propertyReflection->getModifiers();
                $propertyMetadata->isPublic = $propertyReflection->isPublic();

                // Parse property type hint if present
                if (preg_match(AnnotationPropertyResolver::P_PROPERTY_TYPE_HINT, $propertyReflection->getDocComment(), $matches)) {
                    list(, $propertyMetadata->typeHint) = $matches;
                }

                // Process attributes
                foreach ($this->getAttributeConfigurator($propertyDataArray) as $configurator) {
                    /** @var PropertyConfiguratorInterface $configurator Parse property metadata */
                    $configurator->toPropertyMetadata($propertyMetadata);
                }

                // Save property metadata
                $classMetadata->propertiesMetadata[$propertyMetadata->name] = $propertyMetadata;
            }
        }

        return $classMetadata;
    }
}
