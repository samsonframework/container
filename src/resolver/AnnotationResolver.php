<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */

namespace samsonframework\container\resolver;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use samsonframework\container\annotation\Alias;
use samsonframework\container\annotation\AutoWire;
use samsonframework\container\annotation\Controller;
use samsonframework\container\annotation\Inject;
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
     * @param srting $cachePath Path for storing annotation cache
     */
    public function __construct($cachePath)
    {
        $this->reader = new CachedReader(new AnnotationReader(), new FilesystemCache($cachePath));
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return ClassMetadata
     */
    public function resolve(\ReflectionClass $class)
    {
        // Read class annotations
        $classAnnotations = $this->reader->getClassAnnotations($class);

        if ($classAnnotations) {
            $metadata = new ClassMetadata();
            $metadata->className = $class->getName();
            $metadata->internalId = uniqid('container_di', true);

            foreach ($classAnnotations as $annotation) {
                if ($annotation instanceof Service) {
                    $metadata->name = array_key_exists('value', $annotation->name)
                        ? $annotation->name['value']
                        : $metadata->internalId;
                }

                if ($annotation instanceof Scope) {
                    $metadata->scopes = $annotation->scopes;
                }

                if ($annotation instanceof Controller) {
                    $metadata->scopes[] = ControllerScope::SCOPE_NAME;
                }

                if ($annotation instanceof AutoWire) {
                    $metadata->autowire = true;
                }

                if ($annotation instanceof Alias) {
                    $metadata->aliases = $annotation->aliases['value'];
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
        foreach ($class->getMethods() as $method) {
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
