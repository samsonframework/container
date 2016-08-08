<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 19:03
 */
namespace samsonframework\container\tests;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\Service;
use samsonframework\container\MetadataBuilder;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\resolver\AnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\container\resolver\ResolverInterface;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\classes\CarService;
use samsonframework\container\tests\classes\CarServiceWithInterface;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\di\Container;
use samsonframework\filemanager\FileManagerInterface;
use samsonphp\generator\Generator;

class MetadataBuilderTest extends TestCase
{
    /** @var MetadataBuilder */
    protected $container;

    /** @var ResolverInterface */
    protected $resolver;

    /** @var Container */
    protected $diContainer;

    /** @var FileManagerInterface */
    protected $fileManager;

    public function setUp()
    {
        $reader = new AnnotationReader();

        $this->resolver = new AnnotationResolver(
            new AnnotationClassResolver($reader),
            new AnnotationPropertyResolver($reader),
            new AnnotationMethodResolver($reader)
        );
        $this->fileManager = $this->createMock(FileManagerInterface::class);
        $this->diContainer = new \samsonframework\di\Container(new Generator());//$this->createMock(Container::class);

        $this->container = new MetadataBuilder($this->fileManager, $this->resolver, $this->diContainer);
    }

    public function testLoadFromPaths()
    {
        $this->fileManager->method('scan')->willReturn([
            __DIR__ . '/classes/CarController.php',
            __DIR__ . '/classes/Wheel.php',
            __DIR__ . '/classes/Car.php',
        ]);

        $this->container->loadFromPaths([__DIR__ . '/classes/']);
    }

    public function testLoadFromClassNames()
    {
        $this->container->loadFromClassNames([CarController::class, Car::class]);

        static::assertEquals(
            true,
            in_array(CarController::class, $this->getProperty('scopes', $this->container)[MetadataBuilder::SCOPE_CONTROLLER])
        );
        static::assertArrayHasKey(
            CarController::class,
            $this->getProperty('classMetadata', $this->container)
        );
    }

    public function testLoadFromCode()
    {
        $this->container->loadFromCode('class TestClass {} ');
        static::assertArrayHasKey(
            'TestClass',
            $this->getProperty('classMetadata', $this->container)
        );
    }

    public function testBuildServiceConstructorWithClassDependency()
    {
        new Service(['value' => 'service']);

        $this->container
            ->loadFromClassNames([
                CarService::class,
                Car::class,
                FastDriver::class,
                SlowDriver::class,
            ])
            ->build(__DIR__ . '/Container' . uniqid(__CLASS__, true) . '.php');

        // Compile dependency injection container function
        $path = __DIR__ . '/container1.php';
        file_put_contents($path, '<?php ' . $this->diContainer->generateFunction(uniqid('container')));
        require $path;

        static::assertInstanceOf(Car::class, $this->getProperty('car', $this->diContainer->get('car_service')));
    }

    public function testBuildServiceConstructorWithInterfaceDependency()
    {
        new Service(['value' => 'service']);

        $this->container
            ->loadFromClassNames([
                Car::class,
                FastDriver::class,
                SlowDriver::class,
                CarServiceWithInterface::class
            ])
            ->build(__DIR__ . '/Container' . uniqid(__CLASS__, true) . '.php');

        // Compile dependency injection container function
        $path = __DIR__ . '/container2.php';
        file_put_contents($path, '<?php ' . $this->diContainer->generateFunction(uniqid('container')));
        require $path;

        static::assertEquals(Car::class, get_class($this->getProperty('car', $this->diContainer->get('car_service_with_interface'))));
        static::assertEquals(FastDriver::class, get_class($this->getProperty('driver', $this->diContainer->get('car_service_with_interface'))));
    }
}
