<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\resolver;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\MetadataInterface;
use samsonframework\container\annotation\MethodAnnotation;
use samsonframework\container\annotation\MethodInterface;
use samsonframework\container\annotation\PropertyInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;

/**
 * Annotation resolver class.
 */
class AnnotationResolver extends Resolver
{
    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * AnnotationResolver constructor.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $this->reader = new AnnotationReader();
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
        $metadata->internalId = $identifier ?: uniqid();
        $metadata->name = $metadata->internalId;

        $this->resolveClassAnnotations($classData, $metadata);

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
        /** @var MetadataInterface $annotation Read class annotations */
        foreach ($this->reader->getClassAnnotations($classData) as $annotation) {
            if (class_implements($annotation, MetadataInterface::class)) {
                $annotation->toMetadata($metadata);
            }
        }
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
            if (class_implements($annotation, MethodInterface::class)) {

                $methodMetadata->options[$annotation->getMethodAlias()] = $annotation->convertToMetadata();
            }
        }

        $metadata->methodsMetadata[$method->getName()] = $methodMetadata;
    }

    /**
     * Resolve all class property annotations.
     *
     * @param \ReflectionProperty $property
     * @param ClassMetadata       $metadata
     */
    protected function resolveClassPropertyAnnotations(\ReflectionProperty $property, ClassMetadata $metadata)
    {
        /** @var MetadataInterface $annotation Read class annotations */
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if (class_implements($annotation, PropertyInterface::class)) {
                $annotation->toMetadata($metadata);
            }
        }
    }
}
