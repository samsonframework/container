<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 11:25
 */
namespace samsonframework\container\resolver;

use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\ParameterMetadata;

/**
 * Class method resolving trait.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
trait MethodResolverTrait
{
    public function collectionResolve(array $classDataArray, ClassMetadata $classMetadata)
    {
        // Iterate collection
        if (array_key_exists(self::KEY, $classDataArray)) {
            $reflectionClass = new \ReflectionClass($classMetadata->className);

            // Iterate configured methods
            $methodMetadata = new MethodMetadata($classMetadata);
            foreach ($classDataArray[self::KEY] as $methodName => $methodDataArray) {
                $methodReflection = $reflectionClass->getMethod($methodName);

                // Create method metadata instance
                $methodMetadata = clone $methodMetadata;
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

                // Process attributes
                foreach ($this->getAttributeConfigurator($methodDataArray) as $configurator) {
                    /** @var MethodConfiguratorInterface $configurator Parse method metadata */
                    $configurator->toMethodMetadata($methodMetadata);
                }

                // Save method metadata to class metadata
                $classMetadata->methodsMetadata[$methodMetadata->name] = $methodMetadata;
            }
        }

        return $classMetadata;
    }

    /**
     * Resolve class method annotations.
     *
     * @param \ReflectionMethod $method
     * @param ClassMetadata     $classMetadata
     */
    protected function resolveMethodMetadata(\ReflectionMethod $method, ClassMetadata $classMetadata) : MethodMetadata
    {
        // Create method metadata instance
        $methodMetadata = new MethodMetadata($classMetadata);
        $methodMetadata->name = $method->name;
        $methodMetadata->modifiers = $method->getModifiers();
        $methodMetadata->isPublic = $method->isPublic();

        /** @var \ReflectionParameter $parameter */
        $parameterMetadata = new ParameterMetadata($classMetadata, $methodMetadata);
        foreach ($method->getParameters() as $parameter) {
            $parameterMetadata = clone $parameterMetadata;
            $parameterMetadata->name = $parameter->name;
            $parameterMetadata->typeHint = (string)$parameter->getType();

            // Store parameters metadata in method metadata
            $methodMetadata->parametersMetadata[$parameterMetadata->name] = $parameterMetadata;
        }

        // Store method metadata in class metadata
        return $classMetadata->methodsMetadata[$methodMetadata->name] = $methodMetadata;
    }
}
