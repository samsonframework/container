<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\Service;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\CarService;
use samsonframework\container\tests\TestCase;

class ServiceAnnotationTest extends TestCase
{
    public function testToClassMetadata()
    {
        $scope = new Service(['value' => CarService::class]);
        $metadata = new ClassMetadata();
        $scope->toClassMetadata($metadata);
        static::assertEquals(CarService::class, $metadata->name);
    }
}
