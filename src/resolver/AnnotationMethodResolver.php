<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\resolver;

use samsonframework\container\annotation\MethodInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;

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

        return $this->classMetadata;
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
        $methodMetadata = new MethodMetadata();
        $methodMetadata->name = $method->getName();
        $methodMetadata->modifiers = $method->getModifiers();

        /** @var \ReflectionParameter $parameter */
        foreach ($method->getParameters() as $parameter) {
            $methodMetadata->parameters[$parameter->getName()] = $parameter->getType()->__toString();
        }

        /** @var MethodInterface $annotation */
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof MethodInterface) {
                $methodMetadata->options[] = $annotation->toMethodMetadata($methodMetadata);
            }
        }

        $classMetadata->methodsMetadata[$method->getName()] = $methodMetadata;
    }
}
