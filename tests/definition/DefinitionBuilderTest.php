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
            ->addDefinition(Car::class, 'car')->end();

        static::assertEquals($class, $this->getClassDefinition($builder, $class)->getClassName());
        static::assertEquals('car', $this->getClassDefinition($builder, $class)->getServiceName());
    }

    public function testConstructor()
    {
        $class = Car::class;
        $builder = (new DefinitionBuilder())
            ->addDefinition(Car::class, 'car')
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
//
//    public function testMethod()
//    {
//        $builder = new ContainerBuilder();
//        $class = CarController::class;
//        $builder->addDefinition($class)
//            ->defineMethod('setLeg', [
//                'leg' => new ClassReference(Leg::class)
//            ]);
//
//        /** @var MethodDefinition[] $collection */
//        $collection = $this->getProperty('definitionCollection', $builder);
//        $methods = $this->getProperty('methodsCollection', $collection[$class]);
//        $arguments = $this->getProperty('arguments', $methods['setLeg']);
//        static::assertCount(1, $arguments);
//    }
//
//    public function testProperty()
//    {
//        $builder = new ContainerBuilder();
//        $class = CarController::class;
//        $builder->addDefinition($class)->defineProperty('leg', new ClassReference(Leg::class));
//
//        /** @var MethodDefinition[] $collection */
//        $collection = $this->getProperty('definitionCollection', $builder);
//        /** @var PropertyDefinition $property */
//        $properties = $this->getProperty('propertiesCollection', $collection[$class]);
//        $property = $this->getProperty('value', $properties['leg']);
//        static::assertInstanceOf(ClassReference::class, $property);
//    }
//
//    public function testMultipleInjection()
//    {
//        $builder = new ContainerBuilder();
//        $class = WheelController::class;
//        $builder->addDefinition($class)
//            ->defineArguments([
//                'fastDriver' => new ClassReference(DriverInterface::class),
//                'slowDriver' => new ClassReference(SlowDriver::class),
//                'car' => new ServiceReference('car'),
//                'params' => new ResourceReference(['param1' => 'value']),
//                'id' => new ResourceReference('wheel_id')
//            ])
//            ->defineProperty('car', new ServiceReference(Car::class))
//            ->defineMethod('setLeg', [
//                'leg' => new ClassReference(Leg::class)
//            ]);
//
//        /** @var MethodDefinition[] $collection */
//        $collection = $this->getProperty('definitionCollection', $builder);
//        $methods = $this->getProperty('methodsCollection', $collection[$class]);
//        $arguments = $this->getProperty('arguments', $methods['__construct']);
//
//        /** @var MethodDefinition[] $collection */
//        $collection = $this->getProperty('definitionCollection', $builder);
//        $methods = $this->getProperty('methodsCollection', $collection[$class]);
//        $methodArguments = $this->getProperty('arguments', $methods['setLeg']);
//
//        /** @var MethodDefinition[] $collection */
//        $collection = $this->getProperty('definitionCollection', $builder);
//        /** @var PropertyDefinition $property */
//        $properties = $this->getProperty('propertiesCollection', $collection[$class]);
//        $property = $this->getProperty('value', $properties['car']);
//
//        static::assertCount(5, $arguments);
//        static::assertCount(1, $methodArguments);
//        static::assertInstanceOf(ServiceReference::class, $property);
//    }
}
