<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\Inject;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\PropertyMetadata;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\TestCase;

class InjectAnnotationTest extends TestCase
{
    /** @var PropertyMetadata */
    protected $propertyMetadata;

    /** @var PropertyMetadata */
    protected $propertyMetadata2;

    public function setUp()
    {
        $classMetadata = new ClassMetadata();
        $classMetadata->nameSpace = (new \ReflectionClass(Car::class))->getNamespaceName();
        $classMetadata->className = Car::class;
        $this->propertyMetadata = new PropertyMetadata($classMetadata);
        $this->propertyMetadata->name = 'driver';
        $this->propertyMetadata->typeHint = Car::class;

        $this->propertyMetadata2 = new PropertyMetadata($classMetadata);
        $this->propertyMetadata2->name = 'driver';
        $this->propertyMetadata2->typeHint = FastDriver::class;
    }

//    public function testPropertyViolatingInheritance()
//    {
//        $this->expectException(\InvalidArgumentException::class);
//        $inject = new Inject(['value' => CarController::class]);
//        $inject->toPropertyMetadata($this->propertyMetadata);
//    }

    public function testPropertyWithoutTypeHint()
    {
        $inject = new Inject(['value' => Car::class]);
        $inject->toPropertyMetadata($this->propertyMetadata);

        static::assertEquals(Car::class, $this->propertyMetadata->dependency);
    }

//    public function testPropertyWithoutClassNameWithInterfaceTypeHint()
//    {
//        $this->expectException(\InvalidArgumentException::class);
//
//        $inject = new Inject(['value' => '']);
//        $classMetadata = new ClassMetadata();
//        $classMetadata->name = Car::class;
//        $propertyMetadata = new PropertyMetadata($classMetadata);
//        $propertyMetadata->name = 'driver';
//        $propertyMetadata->typeHint = DriverInterface::class;
//
//        $inject->toPropertyMetadata($propertyMetadata);
//    }

    public function testPropertyWithClassNameWithInterfaceTypeHint()
    {
        $inject = new Inject(['value' => FastDriver::class]);
        $inject->toPropertyMetadata($this->propertyMetadata2);

        static::assertEquals(FastDriver::class, $this->propertyMetadata2->dependency);
    }

    public function testPropertyWithoutClassNameWithTypeHint()
    {
        $inject = new Inject(['value' => '']);
        $inject->toPropertyMetadata($this->propertyMetadata2);

        static::assertEquals(FastDriver::class, $this->propertyMetadata2->dependency);
    }

    public function testPropertyWithNamespaceClassNameWithSlash()
    {
        $inject = new Inject(['value' => '\\' . FastDriver::class]);
        $classMetadata = new ClassMetadata();
        $classMetadata->className = Car::class;
        $propertyMetadata = new PropertyMetadata($classMetadata);
        $propertyMetadata->name = 'driver';
        $propertyMetadata->typeHint = FastDriver::class;
        $inject->toPropertyMetadata($propertyMetadata);

        static::assertEquals(FastDriver::class, $propertyMetadata->dependency);
    }

    public function testPropertyWithNamespaceInheritance()
    {
        $inject = new Inject(['value' => Car::class]);
        $classMetadata = new ClassMetadata();
        $classMetadata->className = Car::class;
        $propertyMetadata = new PropertyMetadata($classMetadata);
        $propertyMetadata->name = 'driver';
        $propertyMetadata->typeHint = Car::class;
        $inject->toPropertyMetadata($propertyMetadata);

        static::assertEquals(Car::class, $propertyMetadata->dependency);
    }

//    public function testPropertyWithoutNamespaceInheritance()
//    {
//        $inject = new Inject(['value' => 'Car']);
//        $inject->toPropertyMetadata($this->propertyMetadata);
//
//        static::assertEquals(Car::class, $this->propertyMetadata->dependency);
//    }

//    public function testPropertyServiceNameValue()
//    {
//        $inject = new Inject(['value' => 'car_service']);
//        $classMetadata = new ClassMetadata();
//        $classMetadata->nameSpace = (new \ReflectionClass(Car::class))->getNamespaceName();
//        $propertyMetadata = new PropertyMetadata($classMetadata);
//        $propertyMetadata->typeHint = 'Car';
//        $inject->toPropertyMetadata($propertyMetadata);
//
//        static::assertEquals(Car::class, $propertyMetadata->dependency);
//    }
}
