<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use PHPUnit\Framework\TestCase;
use samsonframework\container\annotation\Service;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\classes\CarService;

class ServiceAnnotationTest extends TestCase
{
    public function testCreationWithArray()
    {
        $serviceName = 'car_service';
        $service = new Service(['value' => $serviceName]);

        static::assertEquals($serviceName, $service->name, true);
    }

    public function testCreationWithWrongType()
    {
        $this->expectException(\Exception::class);

        new Service(new CarController());
    }

    public function testToMetadata()
    {
        $scope = new Service(['value' => CarService::class]);
        $metadata = new ClassMetadata();
        $scope->toMetadata($metadata);
        static::assertEquals(CarService::class, $metadata->name);
    }
}
