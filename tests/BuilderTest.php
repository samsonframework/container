<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests;

use samsonframework\container\Builder;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\PropertyMetadata;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\DriverService;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\classes\Shoes;
use samsonphp\generator\Generator;

class BuilderTest extends TestCase
{
    /** @var  Builder */
    protected $builder;

    public function setUp()
    {
        $generator = new Generator();
        $fastDriverMetadata = new ClassMetadata();
        $fastDriverMetadata->className = FastDriver::class;
        $fastDriverMetadata->name = 'fastdriver';
        $fastDriverMetadata->scopes[] = 'testscope';
        $fastDriverMetadata->propertiesMetadata['leg'] = new PropertyMetadata($fastDriverMetadata);
        $fastDriverMetadata->propertiesMetadata['leg']->dependency = Leg::class;
        $fastDriverMetadata->propertiesMetadata['leg']->isPublic = false;
        $fastDriverMetadata->propertiesMetadata['leg']->name = 'leg';
        $fastDriverMetadata->methodsMetadata['stopCar'] = new MethodMetadata($fastDriverMetadata);
        $fastDriverMetadata->methodsMetadata['stopCar']->dependencies['leg'] = Leg::class;
        $fastDriverMetadata->methodsMetadata['stopCar']->isPublic = true;
        $fastDriverMetadata->methodsMetadata['stopCar']->name = 'stopCar';
        $fastDriverMetadata->methodsMetadata['stopHiddenCar'] = new MethodMetadata($fastDriverMetadata);
        $fastDriverMetadata->methodsMetadata['stopHiddenCar']->dependencies['leg'] = Leg::class;
        $fastDriverMetadata->methodsMetadata['stopHiddenCar']->isPublic = false;
        $fastDriverMetadata->methodsMetadata['stopHiddenCar']->name = 'stopCar';
        $fastDriverMetadata->methodsMetadata['__construct'] = new MethodMetadata($fastDriverMetadata);
        $fastDriverMetadata->methodsMetadata['__construct']->dependencies['leg'] = Leg::class;
        $fastDriverMetadata->methodsMetadata['__construct']->dependencies['array'] = [1, 2, 3];
        $fastDriverMetadata->methodsMetadata['__construct']->dependencies['string'] = 'test string';

        $legMetadata = new ClassMetadata();
        $legMetadata->className = Leg::class;
        $legMetadata->methodsMetadata['pressPedal'] = new MethodMetadata($legMetadata);
        $legMetadata->methodsMetadata['pressPedal']->dependencies['shoes'] = Shoes::class;
        $legMetadata->methodsMetadata['pressPedal']->isPublic = false;
        $legMetadata->methodsMetadata['pressPedal']->name = 'pressPedal';

        $carMetadata = new ClassMetadata();
        $carMetadata->className = Car::class;
        $carMetadata->methodsMetadata['__construct'] = new MethodMetadata($fastDriverMetadata);
        $carMetadata->methodsMetadata['__construct']->dependencies['driver'] = FastDriver::class;

        $shoesMetadata = new ClassMetadata();
        $shoesMetadata->className = Shoes::class;

        $driverServiceMetadata = new ClassMetadata();
        $driverServiceMetadata->className = DriverService::class;
        $driverServiceMetadata->scopes[] = Builder::SCOPE_SERVICES;
        $driverServiceMetadata->propertiesMetadata['car'] = new PropertyMetadata($driverServiceMetadata);
        $driverServiceMetadata->propertiesMetadata['car']->dependency = Car::class;
        $driverServiceMetadata->propertiesMetadata['car']->isPublic = true;
        $driverServiceMetadata->propertiesMetadata['car']->name = 'car';

        $this->builder = new Builder($generator, [
            FastDriver::class => $fastDriverMetadata,
            Leg::class => $legMetadata,
            Car::class => $carMetadata,
            DriverService::class => $driverServiceMetadata,
            Shoes::class => $shoesMetadata
        ]);
    }

    public function testBuild()
    {
        $containerFile = __DIR__ . '/Container2.php';
        file_put_contents($containerFile, $this->builder->build('Container2', 'DI'));
        require $containerFile;

        //eval($this->builder->build('Container2', 'DI'));
        $container = new \DI\Container2();

        // Dependencies
        static::assertInstanceOf(FastDriver::class, $container->getFastdriver());
        static::assertInstanceOf(Leg::class, $this->getProperty('leg', $container->getFastdriver()));

        // Scope
        //static::assertTrue($this->getProperty('testscope', $container) !== null);
    }
}
