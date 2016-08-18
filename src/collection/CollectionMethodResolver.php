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
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\ParameterMetadata;

/**
 * Collection method resolver class.
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class CollectionMethodResolver extends AbstractCollectionResolver implements CollectionResolverInterface
{
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
    public function resolve(array $classDataArray, ClassMetadata $classMetadata)
    {
        // Iterate collection
        if (array_key_exists(self::KEY, $classDataArray)) {
            $reflectionClass = new \ReflectionClass($classMetadata->className);
            // Iterate configured methods
            foreach ($classDataArray[self::KEY] as $methodName => $methodDataArray) {
                $methodReflection = $reflectionClass->getMethod($methodName);

                // Create method metadata instance
                $methodMetadata = new MethodMetadata($classMetadata);
                $methodMetadata->name = $methodReflection->name;
                $methodMetadata->modifiers = $methodReflection->getModifiers();
                $methodMetadata->isPublic = $methodReflection->isPublic();

                // Check if methods inject any instances
                if (array_key_exists(CollectionParameterResolver::KEY, $methodDataArray)) {

                    /** @var \ReflectionParameter $parameter */
                    $parameterMetadata = new ParameterMetadata($methodMetadata->classMetadata, $methodMetadata);
                    // Iterate and create properties metadata form each parameter in method
                    foreach ($methodReflection->getParameters() as $parameter) {
                        $parameterMetadata = clone $parameterMetadata;
                        $parameterMetadata->name = $parameter->name;
                        $parameterMetadata->typeHint = (string)$parameter->getType();

                        // If config has such parameter
                        if (array_key_exists($parameter->name, $methodDataArray[CollectionParameterResolver::KEY])) {
                            $parameterDataArray = $methodDataArray[CollectionParameterResolver::KEY][$parameter->name];
                            // Resolve parameter
                            $this->parameterResolver->resolve($parameterDataArray, $parameterMetadata);
                        }

                        // Store parameter metadata
                        $methodMetadata->parametersMetadata[$parameterMetadata->name] = $parameterMetadata;
                        $methodMetadata->dependencies[$parameterMetadata->name] = $parameterMetadata->dependency;
                    }
                }

                // Iterate collection and resolve method configurator
                if (array_key_exists('@attributes', $methodDataArray)) {
                    // Iterate collection attribute configurators
                    foreach ($this->collectionConfigurators as $key => $collectionConfigurator) {
                        // If this is supported collection configurator
                        if (array_key_exists($key, $methodDataArray['@attributes'])) {
                            /** @var MethodConfiguratorInterface $configurator Create instance */
                            $configurator = new $collectionConfigurator($methodDataArray['@attributes'][$key]);
                            // Fill in class metadata
                            $configurator->toMethodMetadata($methodDataArray);
                        }
                    }
                }

                // Save method metadata
                $classMetadata->methodsMetadata[$methodMetadata->name] = $methodMetadata;
            }
        }

        return $classMetadata;
    }
}
