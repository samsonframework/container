<?php declare(strict_types = 1);
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
    /**
     * Generic class method resolver.
     *
     * @param \ReflectionMethod $method
     * @param ClassMetadata     $classMetadata
     *
     * @return MethodMetadata Resolved method metadata
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
