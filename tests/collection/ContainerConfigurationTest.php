<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Ruslan Molodyko
 * Date: 14.08.16
 * Time: 12:10
 */
namespace samsonframework\container\tests\collection;

use samsonframework\container\collection\attribute\ClassName;
use samsonframework\container\collection\attribute\Name;
use samsonframework\container\collection\attribute\Scope;
use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\collection\CollectionMethodResolver;
use samsonframework\container\collection\CollectionParameterResolver;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\resolver\XmlResolver;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\TestCase;

class ContainerConfigurationTest extends TestCase
{
    public function testConfigure()
    {
        $xmlConfig = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<dependencies>
<instance class="samsonframework\container\\tests\classes\FastDriver" name="MyDriver">
    <argumets>
        
    </argumets>
    <methods>
        <stopCar>
            <arguments>
                <leg class="samsonframework\container\\tests\classes\Leg"></leg>
            </arguments>
        </stopCar>
    </methods>
</instance>
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

        $xmlConfigurator = new XmlResolver(new CollectionClassResolver([
            Scope::class,
            Name::class,
            ClassName::class
        ]), new CollectionPropertyResolver([
            ClassName::class
        ]), new CollectionMethodResolver([], new CollectionParameterResolver([
            ClassName::class
        ])));

        // TODO Not compatible with ContainerBuilder
        $listMetadata = $xmlConfigurator->resolveConfig($xmlConfig);

        static::assertEquals(FastDriver::class, $listMetadata[0]->className);
        static::assertEquals('MyDriver', $listMetadata[0]->name);
        static::assertArrayHasKey('stopCar', $listMetadata[0]->methodsMetadata);
        static::assertTrue($listMetadata[0]->methodsMetadata['stopCar']->isPublic);
        static::assertArrayHasKey('leg', $listMetadata[0]->methodsMetadata['stopCar']->dependencies);
        static::assertEquals(Leg::class, $listMetadata[0]->methodsMetadata['stopCar']->dependencies['leg']);
        static::assertEquals(Car::class, $listMetadata[1]->className);
        static::assertTrue(in_array('myTestScope', $listMetadata[1]->scopes, true));
        static::assertArrayHasKey('driver', $listMetadata[1]->propertiesMetadata);
        static::assertEquals(FastDriver::class, $listMetadata[1]->propertiesMetadata['driver']->dependency);
    }
}