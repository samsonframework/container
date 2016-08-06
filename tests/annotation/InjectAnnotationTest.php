<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\Inject;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\CarController;

class InjectAnnotationTest extends TestCase
{
    public function testToMetadata()
    {
        $scope = new Inject(['value' => CarController::class]);
        $metadata = new ClassMetadata();
        $scope->toMetadata($metadata);
        static::assertEquals(true, in_array(CarController::class, $metadata->aliases));
    }
}
