<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\ContainerBuilder;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\reference\ResourceReference;
use samsonframework\container\definition\reference\ServiceReference;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\classes\DriverInterface;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;


class ContainerBuilderTest extends \samsonframework\container\tests\TestCase
{
    public function testContainerBuilder()
    {
        $builder = new ContainerBuilder();
        $builder->addDefinition(Car::class, 'car');

        $collection = $this->getProperty('definitionCollection', $builder);
        static::assertCount(1, $collection);
    }

    public function testArguments()
    {
        $builder = new ContainerBuilder();
        $class = Car::class;
        $builder->addDefinition($class)
            ->defineArguments([
                'driver' => new ServiceReference('driver')
            ]);

        /** @var MethodDefinition[] $collection */
        $collection = $this->getProperty('definitionCollection', $builder);
        $methods = $this->getProperty('methodsCollection', $collection[$class]);
        $arguments = $this->getProperty('arguments', $methods['__construct']);
        static::assertCount(1, $arguments);
    }

    public function testMethod()
    {
        $builder = new ContainerBuilder();
        $class = CarController::class;
        $builder->addDefinition($class)
            ->defineMethod('setLeg', [
                'leg' => new ClassReference(Leg::class)
            ]);

        /** @var MethodDefinition[] $collection */
        $collection = $this->getProperty('definitionCollection', $builder);
        $methods = $this->getProperty('methodsCollection', $collection[$class]);
        $arguments = $this->getProperty('arguments', $methods['setLeg']);
        static::assertCount(1, $arguments);
    }

    public function testProperty()
    {
        $builder = new ContainerBuilder();
        $class = CarController::class;
        $builder->addDefinition($class)->defineProperty('leg', new ClassReference(Leg::class));

        /** @var MethodDefinition[] $collection */
        $collection = $this->getProperty('definitionCollection', $builder);
        /** @var PropertyDefinition $property */
        $properties = $this->getProperty('propertiesCollection', $collection[$class]);
        $property = $this->getProperty('value', $properties['leg']);
        static::assertInstanceOf(ClassReference::class, $property);
    }

    public function testMultipleInjection()
    {
        $builder = new ContainerBuilder();
        $class = WheelController::class;
        $builder->addDefinition($class)
            ->defineArguments([
                'fastDriver' => new ClassReference(DriverInterface::class),
                'slowDriver' => new ClassReference(SlowDriver::class),
                'car' => new ServiceReference('car'),
                'params' => new ResourceReference(['param1' => 'value']),
                'id' => new ResourceReference('wheel_id')
            ])
            ->defineProperty('car', new ServiceReference(Car::class))
            ->defineMethod('setLeg', [
                'leg' => new ClassReference(Leg::class)
            ]);

        /** @var MethodDefinition[] $collection */
        $collection = $this->getProperty('definitionCollection', $builder);
        $methods = $this->getProperty('methodsCollection', $collection[$class]);
        $arguments = $this->getProperty('arguments', $methods['__construct']);

        /** @var MethodDefinition[] $collection */
        $collection = $this->getProperty('definitionCollection', $builder);
        $methods = $this->getProperty('methodsCollection', $collection[$class]);
        $methodArguments = $this->getProperty('arguments', $methods['setLeg']);

        /** @var MethodDefinition[] $collection */
        $collection = $this->getProperty('definitionCollection', $builder);
        /** @var PropertyDefinition $property */
        $properties = $this->getProperty('propertiesCollection', $collection[$class]);
        $property = $this->getProperty('value', $properties['car']);

        static::assertCount(5, $arguments);
        static::assertCount(1, $methodArguments);
        static::assertInstanceOf(ServiceReference::class, $property);
    }

    protected function getProperty($property, $object)
    {
        $property = (new \ReflectionClass($object))->getProperty($property);
        $property->setAccessible(true);
        try {
            return $property->getValue($object);
        } catch (\Exception $e) {
            return null;
        }
    }
}
