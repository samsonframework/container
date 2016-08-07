<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\Inject;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\PropertyMetadata;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;

class InjectAnnotationTest extends TestCase
{
    public function testToMetadata()
    {
        $scope = new Inject(['value' => CarController::class]);
        $metadata = new MethodMetadata();
        $scope->toMethodMetadata($metadata);
        static::assertEquals(true, in_array(CarController::class, $metadata->dependencies, true));
    }

    public function testPropertyViolatingInheritance()
    {
        $this->expectException(\InvalidArgumentException::class);
        $scope = new Inject(['value' => CarController::class]);
        $propertyMetadata = new PropertyMetadata(new ClassMetadata());
        $propertyMetadata->typeHint = Car::class;
        $scope->toPropertyMetadata($propertyMetadata);
    }

    public function testPropertyViolatingInheritance()
    {
        $this->expectException(\InvalidArgumentException::class);
        $scope = new Inject(['value' => CarController::class]);
        $propertyMetadata = new PropertyMetadata(new ClassMetadata());
        $propertyMetadata->typeHint = Car::class;
        $scope->toPropertyMetadata($propertyMetadata);
    }

    public function testPropertyWithNamespaceInheritance()
    {
        $scope = new Inject(['value' => Car::class]);
        $propertyMetadata = new PropertyMetadata(new ClassMetadata());
        $propertyMetadata->typeHint = Car::class;
        $scope->toPropertyMetadata($propertyMetadata);

        static::assertEquals(Car::class, $propertyMetadata->injectable);
    }

    public function testPropertyWithoutNamespaceInheritance()
    {
        $scope = new Inject(['value' => 'Car']);
        $classMetadata = new ClassMetadata();
        $classMetadata->nameSpace = (new \ReflectionClass(Car::class))->getNamespaceName();
        $propertyMetadata = new PropertyMetadata($classMetadata);
        $propertyMetadata->typeHint = 'Car';
        $scope->toPropertyMetadata($propertyMetadata);

        static::assertEquals(Car::class, $propertyMetadata->injectable);
    }
}
