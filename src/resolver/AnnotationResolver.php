<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */

namespace samsonframework\di\resolver;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use samsonframework\di\annotation\Alias;
use samsonframework\di\annotation\AutoWire;
use samsonframework\di\annotation\Controller;
use samsonframework\di\annotation\Inject;
use samsonframework\di\annotation\MethodAnnotation;
use samsonframework\di\annotation\Scope;
use samsonframework\di\annotation\Service;
use samsonframework\di\metadata\ClassMetadata;
use samsonframework\di\metadata\MethodMetadata;
use samsonframework\di\scope\ControllerScope;

class AnnotationResolver extends Resolver
{
    public static $pathToCache = '../cache/annotation';

    /**
     * @var CachedReader
     */
    protected $reader;

    public function __construct()
    {
        $this->reader = new CachedReader(new AnnotationReader(), new FilesystemCache(self::$pathToCache));
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return ClassMetadata
     */
    public function resolve(\ReflectionClass $class)
    {
        $metadata = new ClassMetadata();
        $classAnnotations = $this->reader->getClassAnnotations($class);

        $metadata->className = $class->getName();
        $metadata->internalId = $this->createInternalId();
        if ($classAnnotations) {
            foreach ($classAnnotations as $annotation) {
                if ($annotation instanceof Service) {
                    $metadata->name = array_key_exists('value', $annotation->name) ? $annotation->name['value'] : $metadata->internalId;
                }
                if ($annotation instanceof Scope) {
                    $metadata->scopes = $annotation->scopes;
                }
                if ($annotation instanceof Controller) {
                    if (!in_array(ControllerScope::SCOPE_NAME, $metadata->scopes)) {
                        array_push($metadata->scopes, ControllerScope::SCOPE_NAME);
                    }
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
