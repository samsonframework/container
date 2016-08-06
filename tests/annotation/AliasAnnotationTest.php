<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use PHPUnit\Framework\TestCase;
use samsonframework\container\annotation\Alias;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\CarController;

class AliasAnnotationTest extends TestCase
{
    public function testToMetadata()
    {
        $scope = new Alias(['value' => CarController::class]);
        $metadata = new ClassMetadata();
        $scope->toMetadata($metadata);
        static::assertEquals(true, in_array(CarController::class, $metadata->aliases));
    }
}
