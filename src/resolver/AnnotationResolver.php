<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\resolver;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\ClassInterface;
use samsonframework\container\annotation\MethodInterface;
use samsonframework\container\annotation\PropertyInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Annotation resolver class.
 */
class AnnotationResolver extends Resolver
{
    /** Property typeHint hint pattern */
    const P_PROPERTY_TYPE_HINT = '/@var\s+(?<class>[^\s]+)/';

    /** @var AnnotationReader */
    protected $reader;

    /**
     * AnnotationResolver constructor.
     *
     * @param $reader
     */
    public function __construct($reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($classData, $identifier = null)
    {
        /** @var \ReflectionClass $classData */

        // Create and fill class metadata base fields
        $metadata = new ClassMetadata();
        $metadata->className = $classData->getName();
        $metadata->nameSpace = $classData->getNamespaceName();
        $metadata->identifier = $identifier ?: uniqid();
        $metadata->name = $metadata->identifier;

        $this->resolveClassAnnotations($classData, $metadata);

        /** @var \ReflectionProperty $property */
        foreach ($classData->getProperties() as $property) {
            $this->resolveClassPropertyAnnotations($property, $metadata);
        }

        /** @var \ReflectionMethod $method */
        foreach ($classData->getMethods() as $method) {
            $this->resolveMethodAnnotation($method, $metadata);
        }

        return $metadata;
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

    /**
     * Resolve all class property annotations.
     *
     * @param \ReflectionProperty $property
     * @param ClassMetadata       $classMetadata
     */
    protected function resolveClassPropertyAnnotations(\ReflectionProperty $property, ClassMetadata $classMetadata)
    {
        // Create method metadata instance
        $propertyMetadata = new PropertyMetadata($classMetadata);
        $propertyMetadata->name = $property->getName();
        $propertyMetadata->modifiers = $property->getModifiers();

        // Parse property type hint if present
        if (preg_match('/@var\s+(?<class>[^\s]+)/', $property->getDocComment(), $matches)) {
            list(, $propertyMetadata->typeHint) = $matches;
        }

        /** @var PropertyInterface $annotation Read class annotations */
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof PropertyInterface) {
                $annotation->toPropertyMetadata($propertyMetadata);
            }
        }

        $classMetadata->propertyMetadata[$propertyMetadata->name] = $propertyMetadata;
    }

    /**
     * Resolve all method annotations.
     *
     * @param \ReflectionMethod $method
     * @param ClassMetadata     $metadata
     */
    protected function resolveMethodAnnotation(\ReflectionMethod $method, ClassMetadata $metadata)
    {
        // Create method metadata instance
        $methodMetadata = new MethodMetadata();
        $methodMetadata->name = $method->getName();
        $methodMetadata->modifiers = $method->getModifiers();
        $methodMetadata->parameters = $method->getParameters();

        /** @var MethodInterface $annotation */
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof MethodInterface) {
                $methodMetadata->options[] = $annotation->toMethodMetadata($methodMetadata);
            }
        }

        $metadata->methodsMetadata[$method->getName()] = $methodMetadata;
    }
}
