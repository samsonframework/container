<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 19:03
 */
namespace samsonframework\container\tests;

use DI\Container;
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
use samsonframework\container\tests\classes\CarServiceWithInterface;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\filemanager\FileManagerInterface;
use samsonphp\generator\Generator;

class MetadataBuilderTest extends TestCase
{
    /** @var MetadataBuilder */
    protected $container;

    /** @var ResolverInterface */
    protected $resolver;

    /** @var FileManagerInterface */
    protected $fileManager;

    /** @var Generator */
    protected $generator;

    public function setUp()
    {
        $reader = new AnnotationReader();

        $this->resolver = new AnnotationResolver(
            new AnnotationClassResolver($reader),
            new AnnotationPropertyResolver($reader),
            new AnnotationMethodResolver($reader)
        );
        $this->generator = new Generator();
        $this->fileManager = $this->createMock(FileManagerInterface::class);

        $this->container = new MetadataBuilder(
            $this->fileManager,
            $this->resolver,
            $this->generator
        );
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

    public function testBuildServiceConstructorWithInterfaceDependency()
    {
        new Service(['value' => 'service']);

        $containerClass = $this->container
            ->loadFromClassNames([
                Car::class,
                CarController::class,
                FastDriver::class,
                SlowDriver::class,
                CarServiceWithInterface::class
            ])
            ->build('Container', 'DI');

        file_put_contents(__DIR__ . '/Container.php', $containerClass);

//        // Compile dependency injection container function
//        $path = __DIR__ . '/container2.php';
//        file_put_contents($path, '<?php ' . $this->diContainer->build(uniqid('container')));

//        static::assertEquals(Car::class, get_class($this->getProperty('car', $this->diContainer->get('car_service_with_interface'))));
//        static::assertEquals(FastDriver::class, get_class($this->getProperty('driver', $this->diContainer->get('car_service_with_interface'))));
    }
}
