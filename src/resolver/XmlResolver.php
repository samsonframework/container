<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 14.08.16 at 15:55
 */
namespace samsonframework\container\resolver;

use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\collection\CollectionMethodResolver;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\metadata\ClassMetadata;

/**
 * XML dependency injection container configuration.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 * @author Ruslan Molodyko  <molodyko@samsonos.com>
 */
class XmlResolver implements ResolverInterface
{
    /** @var CollectionClassResolver */
    protected $classResolver;

    /** @var CollectionPropertyResolver */
    protected $propertyResolver;

    /** @var CollectionMethodResolver */
    protected $methodResolver;

    /**
     * AnnotationResolver constructor.
     *
     * @param CollectionClassResolver $classResolver
     * @param CollectionPropertyResolver $propertyResolver
     * @param CollectionMethodResolver $methodResolver
     */
    public function __construct(
        CollectionClassResolver $classResolver,
        CollectionPropertyResolver $propertyResolver,
        CollectionMethodResolver $methodResolver
    ) {
        $this->classResolver = $classResolver;
        $this->propertyResolver = $propertyResolver;
        $this->methodResolver = $methodResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($classData, string $identifier = null) : ClassMetadata
    {
        /** @var array $classData */

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
