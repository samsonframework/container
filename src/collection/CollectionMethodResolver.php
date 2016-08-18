<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\collection;

use samsonframework\container\configurator\MethodConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\MethodResolverTrait;

/**
 * Collection method resolver class.
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class CollectionMethodResolver extends AbstractCollectionResolver implements CollectionResolverInterface
{
    use MethodResolverTrait;

    /** Collection method key */
    const KEY = 'methods';

    /**
     * @var  CollectionParameterResolver Parameter resolver
     */
    protected $parameterResolver;

    /**
     * CollectionMethodResolver constructor.
     *
     * @param array $collectionConfigurators
     * @param CollectionParameterResolver $parameterResolver
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $collectionConfigurators, CollectionParameterResolver $parameterResolver)
    {
        parent::__construct($collectionConfigurators);

        $this->parameterResolver = $parameterResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(array $configurationArray, ClassMetadata $classMetadata)
    {
        // Iterate collection
        if (array_key_exists(self::KEY, $configurationArray)) {
            $reflectionClass = new \ReflectionClass($classMetadata->className);

            // Iterate configured methods
            foreach ($configurationArray[self::KEY] as $methodName => $methodDataArray) {
                $methodMetadata = $this->resolveMethodMetadata(
                    $reflectionClass->getMethod($methodName),
                    $classMetadata
                );

                // Check if methods inject any instances
                if (array_key_exists(CollectionParameterResolver::KEY, $methodDataArray)) {
                    foreach ($methodMetadata->parametersMetadata as $parameterMetadata) {
                        // If config has parameter
                        if (array_key_exists($parameterMetadata->name, $methodDataArray[CollectionParameterResolver::KEY])) {
                            $this->parameterResolver->resolve(
                                $methodDataArray[CollectionParameterResolver::KEY][$parameterMetadata->name],
                                $parameterMetadata
                            );

                            // Store method dependencies
                            $methodMetadata->dependencies[$parameterMetadata->name] = $parameterMetadata->dependency;
                        }
                    }
                }

                // Process attributes
                foreach ($this->getAttributeConfigurator($methodDataArray) as $configurator) {
                    /** @var MethodConfiguratorInterface $configurator Parse method metadata */
                    $configurator->toMethodMetadata($methodMetadata);
                }
            }
        }

        return $classMetadata;
    }
}
