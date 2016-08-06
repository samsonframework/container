<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use PHPUnit\Framework\TestCase;
use samsonframework\container\annotation\CollectionValue;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\CarController;

class CollectionValueTest extends TestCase
{
    public function testCreationWithEmptyString()
    {
        $scope = new CollectionValue('');

        static::assertEquals([], $scope->collection);
    }

    public function testCreationWithArray()
    {
        $scope = new CollectionValue(['value' => CarController::class]);

        static::assertEquals(true, in_array(CarController::class, $scope->collection, true));
    }

    public function testCreationWithWrongType()
    {
        $this->expectException(\InvalidArgumentException::class);

        new CollectionValue(['value' => new CarController()]);
    }
}
