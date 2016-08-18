<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Ruslan Molodyko
 * Date: 14.08.16
 * Time: 12:10
 */
namespace samsonframework\container;

use samsonframework\container\annotation\Injectable;
use samsonframework\container\collection\CollectionAttributeResolver;
use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\collection\CollectionKeyResolver;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\collection\configurator\ClassName;
use samsonframework\container\collection\configurator\Instance;
use samsonframework\container\collection\configurator\Properties;
use samsonframework\container\collection\configurator\Scope;
use samsonframework\container\collection\configurator\Service;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\XmlResolver;
use samsonframework\container\tests\TestCase;

class ContainerConfigurationTest extends TestCase
{
    public function testConfigure()
    {
        $xmlConfig = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<dependencies>
<instance classname="samsonframework\container\\tests\classes\Car" scope="myTestScope">
    <properties>
        <driver classname="samsonframework\container\\tests\classes\FastDriver"></driver>
    </properties>
</instance>
<service classname="samsonframework\container\ContainerBuilder" name="container">
<arguments>
<fileManager classname="samsonframework\localfilemanager\LocalFileManager"></fileManager>
<keyResolver classname="samsonframework\container\resolver\AnnotationResolver"></keyResolver>
<generator classname="samsonphp\generator\Generator"></generator>
</arguments>
</service>
</dependencies>
XML;

        new Injectable();

        $xmlConfigurator = new XmlResolver(new CollectionKeyResolver([
            Service::class,
            Instance::class,
            Properties::class,
        ], new CollectionAttributeResolver([
            ClassName::class,
            Scope::class
        ])));

        // TODO Not compatible with ContainerBuilder
        $listMetadata = $xmlConfigurator->resolveConfig($xmlConfig);

        $listMetadata[] = new ClassMetadata();

//        $reader = new AnnotationReader();
//
//        $resolver = new AnnotationResolver(
//            new AnnotationClassResolver($reader),
//            new AnnotationPropertyResolver($reader),
//            new AnnotationMethodResolver($reader)
//        );


//        $metadata = $resolver->resolve(new \ReflectionClass(ContainerBuilder::class));
//
//        $container = new ContainerBuilder(new LocalFileManager(), $resolver, new Generator());
//
//        $containerClass = $container->loadFromPaths([realpath(__DIR__ . '/../classes/')]);
//
//        $configData = [];
//
//        foreach ($this->getProperty('classMetadata', $container) as $className => $classMetadata) {
//            $serviceName = $classMetadata->name;
//            if (array_key_exists($serviceName, $configData)) {
//                foreach ($configData[$serviceName] as $propertyName => $dependency) {
//                    if (array_key_exists($propertyName, $classMetadata->propertiesMetadata)) {
//                        $classMetadata->propertiesMetadata[$propertyName]->dependency = ltrim($dependency, '\\');
//                    }
//                }
//            }
//        }
//
//        $containerClass = $containerClass->build('Container', 'DI');
//
//        $path = __DIR__ . '/Container2.php';
//        file_put_contents($path, $containerClass);

//        if (!class_exists(Container::class, false)) {
//            require_once __DIR__ . 'ContainerConfiguration.php/' . $className;
//        }

        //return new \DI\Container($generator);

        //$container = $containerConfig->configure(null, $configData);

        //static::assertInstanceOf(Logger::class, $container->getReader()->logger);
    }
}