<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\annotation;

use samsonframework\container\configurator\MethodConfiguratorInterface;
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
    public function resolve(\ReflectionClass $classReflection, ClassMetadata $classMetadata)
    {
        /** @var \ReflectionMethod $method */
        foreach ($classReflection->getMethods() as $method) {
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
        $methodMetadata->name = $method->name;
        $methodMetadata->modifiers = $method->getModifiers();
        $methodMetadata->isPublic = $method->isPublic();

        /** @var \ReflectionParameter $parameter */
        $parameterMetadata = new ParameterMetadata($classMetadata, $methodMetadata);
        foreach ($method->getParameters() as $parameter) {
            $parameterMetadata = clone $parameterMetadata;
            $parameterMetadata->name = $parameter->name;
            $parameterMetadata->typeHint = (string)$parameter->getType();
            $methodMetadata->parametersMetadata[$parameterMetadata->name] = $parameterMetadata;
        }

        /** @var MethodInterface $annotation */
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof MethodConfiguratorInterface) {
                $annotation->toMethodMetadata($methodMetadata);
            }
        }

        $classMetadata->methodsMetadata[$methodMetadata->name] = $methodMetadata;
    }
}
