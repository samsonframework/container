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
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class CollectionPropertyResolver extends AbstractCollectionResolver implements CollectionResolverInterface
{
    /** Collection class key */
    const KEY = 'properties';

    /**
     * {@inheritDoc}
     */
    public function resolve(array $classDataArray, ClassMetadata $classMetadata)
    {
        // Iterate collection
        if (array_key_exists(self::KEY, $classDataArray)) {
            $reflectionClass = new \ReflectionClass($classMetadata->className);
            // Iterate configured properties
            foreach ($classDataArray[self::KEY] as $propertyName => $propertyDataArray) {
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

                // Iterate collection
                if (array_key_exists('@attributes', $propertyDataArray)) {
                    // Iterate collection attribute configurators
                    foreach ($this->configurators as $key => $collectionConfigurator) {
                        // If this is supported collection configurator
                        if (array_key_exists($key, $propertyDataArray['@attributes'])) {
                            /** @var PropertyConfiguratorInterface $configurator Create instance */
                            $configurator = new $collectionConfigurator($propertyDataArray['@attributes'][$key]);
                            // Fill in class metadata
                            $configurator->toPropertyMetadata($propertyMetadata);
                        }
                    }
                }

                // Save property metadata
                $classMetadata->propertiesMetadata[$propertyMetadata->name] = $propertyMetadata;
            }
        }

        return $classMetadata;
    }
}
