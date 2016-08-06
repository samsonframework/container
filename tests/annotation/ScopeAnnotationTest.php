<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use PHPUnit\Framework\TestCase;
use samsonframework\container\annotation\Scope;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\CarController;

class ScopeAnnotationTest extends TestCase
{
    public function testCreationWithEmptyString()
    {
        $scope = new Scope('');

        static::assertEquals([], $scope->scopes);
    }

    public function testCreationWithArray()
    {
        $scope = new Scope(['value' => CarController::class]);

        static::assertEquals(true, in_array(CarController::class, $scope->scopes, true));
    }

    public function testCreationWithWrongType()
    {
        $this->expectException(\Exception::class);

        new Scope(['value' => new CarController()]);
    }

    public function testToMetadata()
    {
        $scope = new Scope(['value' => CarController::class]);
        $metadata = new ClassMetadata();
        $scope->toMetadata($metadata);
        static::assertEquals(true, in_array(CarController::class, $metadata->scopes));
    }
}
