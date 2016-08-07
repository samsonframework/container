<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\resolver;

use samsonframework\container\annotation\MethodInterface;
use samsonframework\container\annotation\ParameterInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\ParameterMetadata;

/**
 * Class method annotation resolver.
 */
class AnnotationMethodResolver extends AbstractAnnotationResolver implements AnnotationResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve(\ReflectionClass $classData, ClassMetadata $classMetadata)
    {
        /** @var \ReflectionMethod $method */
        foreach ($classData->getMethods() as $method) {
            $this->resolveMethodAnnotations($method, $classMetadata);
        }

        return $classMetadata;
    }

    /**
     * Resolve class method annotations.
     *
     * @param \ReflectionMethod $method
     * @param ClassMetadata     $classMetadata
     */
    protected function resolveMethodAnnotations(\ReflectionMethod $method, ClassMetadata $classMetadata)
    {
        // Create method metadata instance
        $methodMetadata = new MethodMetadata($classMetadata);
        $methodMetadata->name = $method->getName();
        $methodMetadata->modifiers = $method->getModifiers();

        /** @var \ReflectionParameter $parameter */
        $parameterMetadata = new ParameterMetadata($classMetadata, $methodMetadata);
        foreach ($method->getParameters() as $parameter) {
            $parameterMetadata = clone $parameterMetadata;
            $parameterMetadata->name = $parameter->getName();
            $parameterMetadata->typeHint = $parameter->getType()->__toString();
            $methodMetadata->parametersMetadata[$parameterMetadata->name] = $parameterMetadata;
        }

        /** @var MethodInterface|ParameterInterface $annotation */
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof MethodInterface) {
                $annotation->toMethodMetadata($methodMetadata);
            }
            if ($annotation instanceof ParameterInterface) {
                $annotation->toParameterMetadata(new ParameterMetadata($methodMetadata));
            }
        }

        $classMetadata->methodsMetadata[$method->getName()] = $methodMetadata;
    }
}
