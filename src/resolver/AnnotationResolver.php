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

        /** @var MetadataInterface $annotation Read class annotations */
        foreach ($this->reader->getClassAnnotations($classData) as $annotation) {
            if (class_implements($annotation, MetadataInterface::class)) {
                $annotation->toMetadata($metadata);
            }
        }

        /** @var \ReflectionMethod $method */
        foreach ($classData->getMethods() as $method) {
            $methodAnnotations = $this->reader->getMethodAnnotations($method);
            $methodMetadata = new MethodMetadata();
            $methodMetadata->name = $method->getName();
            $methodMetadata->modifiers = $method->getModifiers();
            $methodMetadata->parameters = $method->getParameters();

            /** @var MethodAnnotation $methodAnnotation */
            foreach ($methodAnnotations as $methodAnnotation) {
                $methodMetadata->options[$methodAnnotation->getMethodAlias()] = $methodAnnotation->convertToMetadata();
            }
            $metadata->methodsMetadata[$method->getName()] = $methodMetadata;
        }

        return $metadata;
    }
}
