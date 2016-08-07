<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\resolver;

use Doctrine\Common\Annotations\Reader;
use samsonframework\container\annotation\ClassInterface;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Annotation resolver class.
 */
class AnnotationResolver implements Resolver
{
    /** @var Reader */
    protected $reader;

    /** @var Resolver */
    protected $propertyResolver;

    /** @var Resolver */
    protected $methodResolver;

    /**
     * AnnotationResolver constructor.
     *
     * @param Reader   $reader
     * @param Resolver $propertyResolver
     * @param Resolver $methodResolver
     */
    public function __construct(Reader $reader, Resolver $propertyResolver, Resolver $methodResolver)
    {
        $this->reader = $reader;
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
        $classMetadata->className = $classData->getName();
        $classMetadata->nameSpace = $classData->getNamespaceName();
        $classMetadata->identifier = $identifier ?: uniqid();
        $classMetadata->name = $classMetadata->identifier;

        $this->resolveClassAnnotations($classData, $classMetadata);

        $this->propertyResolver->resolve($classData, $identifier);
        $this->methodResolver->resolve($classData, $identifier);


        return $classMetadata;
    }

    /**
     * Resolve all class annotations.
     *
     * @param \ReflectionClass $classData
     * @param ClassMetadata    $metadata
     */
    protected function resolveClassAnnotations(\ReflectionClass $classData, ClassMetadata $metadata)
    {
        /** @var ClassInterface $annotation Read class annotations */
        foreach ($this->reader->getClassAnnotations($classData) as $annotation) {
            if ($annotation instanceof ClassInterface) {
                $annotation->toClassMetadata($metadata);
            }
        }
    }
}
