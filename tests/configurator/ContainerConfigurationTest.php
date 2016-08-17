<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Ruslan Molodyko
 * Date: 14.08.16
 * Time: 12:10
 */
namespace samsonframework\container;

use samsonframework\container\annotation\Injectable;
use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\collection\Instance;
use samsonframework\container\collection\Scope;
use samsonframework\container\resolver\XmlResolver;
use samsonframework\container\tests\TestCase;

class ContainerConfigurationTest extends TestCase
{
    public function testConfigure()
    {
        $xmlConfig = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<dependencies>
<instance class="samsonframework\container\\tests\classes\Car" scope="myTestScope">
    <properties>
        <driver class="samsonframework\container\\tests\classes\FastDriver"></driver>
    </properties>
</instance>
<service class="samsonframework\container\ContainerBuilder" name="container">
<arguments>
<fileManager class="samsonframework\localfilemanager\LocalFileManager"></fileManager>
<classResolver class="samsonframework\container\resolver\AnnotationResolver"></classResolver>
<generator class="samsonphp\generator\Generator"></generator>
</arguments>
</service>
</dependencies>
XML;

        new Injectable();

        $xmlConfigurator = new XmlResolver(new CollectionClassResolver([
            Scope::class,
            Instance::class
        ]), new CollectionPropertyResolver([
            Instance::class
        ]));

        // TODO Not compatible with ContainerBuilder
        $listMetadata = $xmlConfigurator->resolveConfig($xmlConfig);

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