<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\Alias;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\TestCase;

class AliasAnnotationTest extends TestCase
{
    public function testToMetadata()
    {
        $scope = new Alias(['value' => CarController::class]);
        $metadata = new ClassMetadata();
        $scope->toClassMetadata($metadata);
        static::assertEquals(CarController::class, $metadata->alias);
    }
}
