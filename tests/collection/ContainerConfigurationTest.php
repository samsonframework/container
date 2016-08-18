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
use samsonframework\container\collection\attribute\Service;
use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\collection\CollectionMethodResolver;
use samsonframework\container\collection\CollectionParameterResolver;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\ContainerBuilder;
use samsonframework\container\resolver\XmlResolver;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\TestCase;
use samsonframework\container\XMLContainerBuilder;
use samsonframework\localfilemanager\LocalFileManager;
use samsonphp\generator\Generator;

class ContainerConfigurationTest extends TestCase
{
    public function testConfigure()
    {
        $xmlConfig = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<dependencies>
<instance class="samsonframework\container\\tests\classes\FastDriver" name="MyDriver">
    <methods>
        <__construct>
            <arguments>
                <leg class="samsonframework\container\\tests\classes\Leg"></leg>
            </arguments>
        </__construct>
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
<instance class="samsonframework\container\\tests\classes\CarService" service="carservice">
    <methods>
        <__construct>
            <arguments>
                <car class="samsonframework\container\\tests\classes\Car"></car>
                <driver class="samsonframework\container\\tests\classes\FastDriver"></driver>
            </arguments>
        </__construct>
    </methods>
</instance>
<instance class="samsonframework\container\\tests\classes\Road">
    <methods>
        <__construct>
            <arguments>
                <carService service="carservice"></carService>
            </arguments>
        </__construct>
    </methods>
</instance>
</dependencies>
XML;

        $xmlConfigurator = new XmlResolver(new CollectionClassResolver([
            Scope::class,
            Name::class,
            ClassName::class,
            Service::class
        ]), new CollectionPropertyResolver([
            ClassName::class
        ]), new CollectionMethodResolver([], new CollectionParameterResolver([
            ClassName::class,
            Service::class
        ])));

        // TODO Not compatible with ContainerBuilder
        $container = new XMLContainerBuilder($xmlConfig, new LocalFileManager(), $xmlConfigurator, new Generator());
        file_put_contents(__DIR__ . '/Container.php', $container->build());

        $listMetadata = $this->getProperty('classMetadata', $container);

        $fastDriverMetadata = array_shift($listMetadata);
        $carMetadata = array_shift($listMetadata);
        $carServiceMetadata = array_shift($listMetadata);
        $roadMetadata = array_shift($listMetadata);

        static::assertEquals(FastDriver::class, $fastDriverMetadata->className);
        static::assertEquals('MyDriver', $fastDriverMetadata->name);
        static::assertArrayHasKey('stopCar', $fastDriverMetadata->methodsMetadata);
        static::assertTrue($fastDriverMetadata->methodsMetadata['stopCar']->isPublic);
        static::assertArrayHasKey('leg', $fastDriverMetadata->methodsMetadata['stopCar']->dependencies);
        static::assertEquals(Leg::class, $fastDriverMetadata->methodsMetadata['stopCar']->dependencies['leg']);

        // Injecting constructor dependency
        static::assertTrue(in_array(Leg::class, $fastDriverMetadata->methodsMetadata['__construct']->dependencies, true));

        static::assertEquals(Car::class, $carMetadata->className);
        static::assertTrue(in_array('myTestScope', $carMetadata->scopes, true));
        static::assertArrayHasKey('driver', $carMetadata->propertiesMetadata);
        static::assertEquals(FastDriver::class, $carMetadata->propertiesMetadata['driver']->dependency);


        // Service method attribute for adding to services scope in class
        static::assertTrue(in_array(ContainerBuilder::SCOPE_SERVICES, $carServiceMetadata->scopes, true));
        // Service method attribute for adding name in class
        static::assertEquals('carservice', $carServiceMetadata->name);

        // Service method attribute for injecting as constructor dependency
        static::assertTrue(in_array('carservice', $roadMetadata->methodsMetadata['__construct']->dependencies, true));


    }
}
