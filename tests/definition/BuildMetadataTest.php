<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\ClassDefinition;
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
use samsonframework\container\tests\TestCase;

class BuildMetadataTest extends \PHPUnit\Framework\TestCase
{
    public function testContainerBuilder()
    {
        $class = WheelController::class;
        $classDefinition = (new ClassDefinition($class))
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

        /** @var ClassMetadata $classMetadata */
        $classMetadata = $classDefinition->toMetadata();
        static::assertEquals($class, $classMetadata->className);
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
