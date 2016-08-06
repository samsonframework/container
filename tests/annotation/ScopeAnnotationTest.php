<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use PHPUnit\Framework\TestCase;
use samsonframework\container\annotation\Scope;
use samsonframework\container\tests\classes\CarController;

class ScopeAnnotationTest extends TestCase
{
    public function testCreationWithEmptyString()
    {
        $scope = new Scope('');

        $this->assertEquals([], $scope->scopes);
    }

    public function testCreationWithArray()
    {
        $scope = new Scope(['value' => CarController::class]);

        $this->assertEquals(true, in_array(CarController::class, $scope->scopes));
    }

    public function testCreationWithWrondType()
    {
        $this->expectException(\Exception::class);

        $scope = new Scope(['value' => new CarController()]);
    }
}
