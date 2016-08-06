<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\resolver;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use samsonframework\container\annotation\Alias;
use samsonframework\container\annotation\AutoWire;
use samsonframework\container\annotation\Controller;
use samsonframework\container\annotation\Inject;
use samsonframework\container\annotation\MetadataInterface;
use samsonframework\container\annotation\MethodAnnotation;
use samsonframework\container\annotation\Scope;
use samsonframework\container\annotation\Service;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\scope\ControllerScope;

class AnnotationResolver extends Resolver
{
    /**
     * @var CachedReader
     */
    protected $reader;

    /**
     * AnnotationResolver constructor.
     *
     * @param string $cachePath Path for storing annotation cache
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($cachePath)
    {
        $this->reader = new CachedReader(new AnnotationReader(), new FilesystemCache($cachePath));
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($classData, $identifier = null)
    {
        /** @var \ReflectionClass $classData */

        /** @var MetadataInterface[] $classAnnotations Read class annotations */
        $classAnnotations = $this->reader->getClassAnnotations($classData);

        if ($classAnnotations) {
            $metadata = new ClassMetadata();
            $metadata->className = $classData->getName();
            $metadata->internalId = $identifier ?: uniqid('container', true);

            foreach ($classAnnotations as $annotation) {
                if (class_implements($annotation, MetadataInterface::class)) {
                    $annotation->toMetadata($metadata, $classData->getName());
                }

                if ($annotation instanceof Inject) {
                    $argumentList = $annotation->list['value'];
                    foreach ($argumentList as $name => $serviceName) {
                        $arg = ['service' => $serviceName];
                        if (is_string($name)) {
                            $metadata->args[$name] = $arg;
                        } else {
                            $metadata->args[] = $arg;
                        }
                    }
                }
            }

            if (!$metadata->name) {
                $metadata->name = $metadata->internalId;
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
