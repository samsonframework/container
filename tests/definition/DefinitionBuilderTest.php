<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\DefinitionBuilder;
use samsonframework\container\definition\ParameterDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\reference\ResourceReference;
use samsonframework\container\definition\reference\ServiceReference;
use samsonframework\container\exception\ParentDefinitionNotFoundException;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\classes\DriverInterface;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;
use samsonframework\container\tests\TestCaseDefinition;


class DefinitionBuilderTest extends TestCaseDefinition
{
    public function testAddDefinition()
    {
        $class = Car::class;
        $builder = (new DefinitionBuilder())
            ->addDefinition($class, 'car')->end();

        static::assertEquals($class, $this->getClassDefinition($builder, $class)->getClassName());
        static::assertEquals('car', $this->getClassDefinition($builder, $class)->getServiceName());
    }

    public function testConstructor()
    {
        $class = Car::class;
        $builder = (new DefinitionBuilder())
            ->addDefinition($class, 'car')
                ->defineConstructor()
                    ->defineParameter('driver')
                        ->defineDependency(new ClassReference(FastDriver::class))
                    ->end()
                ->end()
            ->end()
        ;

        $method = '__construct';

        static::assertInstanceOf(MethodDefinition::class, $this->getMethodDefinition($builder, $class, $method));
        static::assertInstanceOf(
            ParameterDefinition::class,
            $this->getParameterDefinition($builder, $class, $method, 'driver')
        );
        static::assertInstanceOf(
            ClassReference::class,
            $this->getParameterDefinition($builder, $class, $method, 'driver')->getDependency()
        );
    }

    public function testMethod()
    {
        $class = CarController::class;
        $method = 'setLeg';
        $builder = (new DefinitionBuilder())
            ->addDefinition($class)
                ->defineMethod($method)
                    ->defineParameter('leg')
                        ->defineDependency(new ClassReference(Leg::class))
                    ->end()
                ->end()
            ->end()
        ;

        static::assertInstanceOf(MethodDefinition::class, $this->getMethodDefinition($builder, $class, $method));
        static::assertInstanceOf(
            ParameterDefinition::class,
            $this->getParameterDefinition($builder, $class, $method, 'leg')
        );
        static::assertInstanceOf(
            ClassReference::class,
            $this->getParameterDefinition($builder, $class, $method, 'leg')->getDependency()
        );
    }

    public function testProperty()
    {
        $class = CarController::class;
        $property = 'car';
        $builder = (new DefinitionBuilder())
            ->addDefinition($class)
                ->defineProperty($property)
                    ->defineDependency(new ClassReference(Leg::class))
                ->end()
            ->end()
        ;

        $propertyDefinition = $this->getPropertyDefinition($builder, $class, $property);
        static::assertInstanceOf(PropertyDefinition::class, $propertyDefinition);
        static::assertInstanceOf(
            ClassReference::class,
            $propertyDefinition->getDependency()
        );
    }

    public function testEndBuilder()
    {
        $class = CarController::class;
        $builder = (new DefinitionBuilder())->addDefinition($class)->end();

        $this->expectException(ParentDefinitionNotFoundException::class);
        $builder->end();
    }
}
