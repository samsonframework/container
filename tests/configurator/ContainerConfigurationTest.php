<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Ruslan Molodyko
 * Date: 14.08.16
 * Time: 12:10
 */
namespace samsonframework\container;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\Injectable;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\resolver\AnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\container\resolver\ArrayClassResolver;
use samsonframework\container\resolver\XMLResolver;
use samsonframework\container\tests\TestCase;
use samsonframework\localfilemanager\LocalFileManager;
use samsonphp\generator\Generator;

class ContainerConfigurationTest extends TestCase
{
    public function testConfigure()
    {
        $xmlConfig = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<dependency>
    <container class="samsonframework\container\ContainerBuilder">
        <controller></controller>
        <service>TestXmlService</service>
        <scope>TestScope</scope>
        <constructor>
            <fileManager class="samsonframework\localfilemanager\LocalFileManager"></fileManager>
            <classResolver class="samsonframework\container\AnnotationResolver">
                <classResolver class="samsonframework\\container\\AnnotationClassResolver">
                    <reader class="Doctrine\Common\Annotations\AnnotationReader"></reader>
                </classResolver>545
                <propertyResolver class="samsonframework\\container\\AnnotationPropertyResolver">
                    <reader class="Doctrine\Common\Annotations\AnnotationReader"></reader>
                </propertyResolver>
                <methodResolver class="samsonframework\\container\\AnnotationMethodResolver">
                    <reader class="Doctrine\Common\Annotations\AnnotationReader"></reader>
                </methodResolver>
            </classResolver>
            <generator class="samsonphp\generator\Generator"></generator>
        </constructor>
    </container>
    <car_service class=""></car_service>
</dependency>
XML;

        new Injectable();
        $xmlConfigurator = new XMLResolver(new ArrayClassResolver());
        $data = $xmlConfigurator->resolve($xmlConfig);


        //$xmlResolver = new XMLResolver();
        //$xmlResolver->resolve($xml);


        $reader = new AnnotationReader();

        $resolver = new AnnotationResolver(
            new AnnotationClassResolver($reader),
            new AnnotationPropertyResolver($reader),
            new AnnotationMethodResolver($reader)
        );


        $metadata = $resolver->resolve(new \ReflectionClass(ContainerBuilder::class));

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