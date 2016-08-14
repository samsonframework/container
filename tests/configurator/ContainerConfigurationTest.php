<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Ruslan Molodyko
 * Date: 14.08.16
 * Time: 12:10
 */
namespace samsonframework\container;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\resolver\AnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\container\tests\TestCase;
use samsonframework\localfilemanager\LocalFileManager;
use samsonphp\generator\Generator;

class ContainerConfigurationTest extends TestCase
{
    public function testConfigure()
    {
        $xmlConfig = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<container>
    <container_builder>
        <fileManager>samsonframework\localfilemanager\LocalFileManager</fileManager>
        <classResolver>samsonframework\container\\resolver\AnnotationClassResolver</classResolver>
        <generator>samsonphp\generator\Generator</generator>
    </container_builder>
  <car_service>
    <car>\samsonframework\container\\tests\classes\Car</car>
    <driver>samsonframework\container\\tests\classes\Driver</driver>
  </car_service>
</container>
XML;

        $reader = new AnnotationReader();

        $resolver = new AnnotationResolver(
            new AnnotationClassResolver($reader),
            new AnnotationPropertyResolver($reader),
            new AnnotationMethodResolver($reader)
        );

        $container = new ContainerBuilder(new LocalFileManager(), $resolver, new Generator());

        $containerClass = $container->loadFromPaths([realpath(__DIR__ . '/../classes/')]);

        $configData = [];

        foreach ($this->getProperty('classMetadata', $container) as $className => $classMetadata) {
            $serviceName = $classMetadata->name;
            if (array_key_exists($serviceName, $configData)) {
                foreach ($configData[$serviceName] as $propertyName => $dependency) {
                    if (array_key_exists($propertyName, $classMetadata->propertiesMetadata)) {
                        $classMetadata->propertiesMetadata[$propertyName]->dependency = ltrim($dependency, '\\');
                    }
                }
            }
        }

        $containerClass = $containerClass->build('Container', 'DI');

        $path = __DIR__ . '/Container2.php';
        file_put_contents($path, $containerClass);

//        if (!class_exists(Container::class, false)) {
//            require_once __DIR__ . 'ContainerConfiguration.php/' . $className;
//        }

        //return new \DI\Container($generator);

        //$container = $containerConfig->configure(null, $configData);

        //static::assertInstanceOf(Logger::class, $container->getReader()->logger);
    }
}