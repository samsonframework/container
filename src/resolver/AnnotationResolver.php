<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:32
 */
namespace samsonframework\container\resolver;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Annotation resolver implementation.
 *
 * @package samsonframework\container\resolver
 */
class AnnotationResolver implements Resolver
{
    /** @var AnnotationResolverInterface */
    protected $classResolver;

    /** @var AnnotationResolverInterface */
    protected $propertyResolver;

    /** @var AnnotationResolverInterface */
    protected $methodResolver;

    /**
     * AnnotationResolver constructor.
     *
     * @param AnnotationResolverInterface $classResolver
     * @param AnnotationResolverInterface $propertyResolver
     * @param AnnotationResolverInterface $methodResolver
     */
    public function __construct(AnnotationResolverInterface $classResolver, AnnotationResolverInterface $propertyResolver, AnnotationResolverInterface $methodResolver)
    {
        $this->classResolver = $classResolver;
        $this->propertyResolver = $propertyResolver;
        $this->methodResolver = $methodResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($classData, $identifier = null)
    {
        /** @var \ReflectionClass $classData */

        // Create and fill class metadata base fields
        $classMetadata = new ClassMetadata();

        // Resolve class definition annotations
        $this->classResolver->resolve($classData, $classMetadata);
        // Resolve class properties annotations
        $this->propertyResolver->resolve($classData, $classMetadata);
        // Resolve class methods annotations
        $this->methodResolver->resolve($classData, $classMetadata);

        return $classMetadata;
    }
}
