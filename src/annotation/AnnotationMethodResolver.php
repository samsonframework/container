<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\annotation;

use samsonframework\container\configurator\MethodConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\resolver\MethodResolverTrait;

/**
 * Class method annotation resolver.
 */
class AnnotationMethodResolver extends AbstractAnnotationResolver implements AnnotationResolverInterface
{
    use MethodResolverTrait;

    /**
     * {@inheritDoc}
     */
    public function resolve(\ReflectionClass $classReflection, ClassMetadata $classMetadata)
    {
        /** @var \ReflectionMethod $method */
        foreach ($classReflection->getMethods() as $method) {
            $this->resolveMethodAnnotations(
                $method,
                $this->resolveMethodMetadata($method, $classMetadata)
            );
        }

        return $classMetadata;
    }

    /**
     * Resolve class method annotations.
     *
     * @param \ReflectionMethod $method
     * @param MethodMetadata    $methodMetadata
     */
    protected function resolveMethodAnnotations(\ReflectionMethod $method, MethodMetadata $methodMetadata)
    {
        /** @var MethodConfiguratorInterface $annotation */
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof MethodConfiguratorInterface) {
                $annotation->toMethodMetadata($methodMetadata);
            }
        }
    }
}
