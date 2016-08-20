<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 12:50
 */
namespace samsonframework\container\tests;

use samsonframework\container\collection\attribute\ClassName;
use samsonframework\container\collection\attribute\Name;
use samsonframework\container\collection\attribute\Scope;
use samsonframework\container\collection\attribute\Service;
use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\collection\CollectionMethodResolver;
use samsonframework\container\collection\CollectionParameterResolver;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\XmlResolver;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Road;
use samsonframework\container\XmlMetadataCollector;

class XmlMetadataCollectorTest extends TestCase
{
    public function testCollect()
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

        $xmlCollector = new XmlMetadataCollector($xmlConfigurator);

        /** @var ClassMetadata[] $classesMetadata */
        $classesMetadata = $xmlCollector->collect($xmlConfig);

        static::assertEquals(FastDriver::class, $classesMetadata[0]->className);
        static::assertEquals(Road::class, $classesMetadata[3]->className);
    }
}
