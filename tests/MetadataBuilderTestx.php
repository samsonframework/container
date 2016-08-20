<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 19:03
 */
namespace samsonframework\container\tests;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\AnnotationClassResolver;
use samsonframework\container\annotation\AnnotationMethodResolver;
use samsonframework\container\annotation\AnnotationPropertyResolver;
use samsonframework\container\annotation\AnnotationResolver;
use samsonframework\container\annotation\Service;
use samsonframework\container\Builder;
use samsonframework\container\resolver\ResolverInterface;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\classes\CarServiceWithInterface;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\filemanager\FileManagerInterface;
use samsonphp\generator\Generator;

class MetadataBuilderTestx extends TestCase
{
    /** @var Builder */
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

        $this->container = new Builder(
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
            in_array(CarController::class, $this->getProperty('scopes', $this->container)[Builder::SCOPE_CONTROLLER])
        );
        static::assertArrayHasKey(
            CarController::class,
            $this->getProperty('classesMetadata', $this->container)
        );
    }

    public function testLoadFromCode()
    {
        $this->container->loadFromCode('class TestClass {} ');
        static::assertArrayHasKey(
            'TestClass',
            $this->getProperty('classesMetadata', $this->container)
        );
    }

    public function testBuildServiceConstructorWithInterfaceDependency()
    {
        new Service(['value' => 'service']);

        // Build container
        $containerClass = $this->container
            ->loadFromClassNames([
                Car::class,
                Leg::class,
                CarController::class,
                FastDriver::class,
                SlowDriver::class,
                CarServiceWithInterface::class
            ])
            ->build('Container', 'DI');

        // Execute container class
        $path = __DIR__ . '/Container.php';
        file_put_contents($path, $containerClass);
        require $path;
        $container = new \DI\Container();

        static::assertInstanceOf(Car::class, $container->getSamsonframeworkContainerTestsClassesCar());
        static::assertInstanceOf(CarServiceWithInterface::class, $container->getCarServiceWithInterface());
        static::assertInstanceOf(Leg::class, $this->getProperty('leg', $container->getCarServiceWithInterface()));
    }
}
