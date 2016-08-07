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
    /** @var Resolver */
    protected $classResolver;

    /** @var Resolver */
    protected $propertyResolver;

    /** @var Resolver */
    protected $methodResolver;

    /**
     * AnnotationResolver constructor.
     *
     * @param Resolver $classResolver
     * @param Resolver $propertyResolver
     * @param Resolver $methodResolver
     */
    public function __construct(Resolver $classResolver, Resolver $propertyResolver, Resolver $methodResolver)
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
