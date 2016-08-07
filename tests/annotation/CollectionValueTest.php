<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\AnnotationWithValue;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\TestCase;

class CollectionValueTest extends TestCase
{
    public function testCreationWithEmptyString()
    {
        $scope = new AnnotationWithValue('');

        static::assertEquals([], $this->getProperty('collection', $scope));
    }

    public function testCreationWithArray()
    {
        $scope = new AnnotationWithValue(['value' => CarController::class]);

        static::assertEquals(true, in_array(CarController::class, $this->getProperty('collection', $scope), true));
    }

    public function testCreationWithWrongType()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AnnotationWithValue(['value' => new CarController()]);
    }
}
