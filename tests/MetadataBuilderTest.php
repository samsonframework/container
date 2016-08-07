<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 19:03
 */
namespace samsonframework\container\tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Interop\Container\ContainerInterface;
use samsonframework\container\MetadataBuilder;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\resolver\AnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\container\resolver\ResolverInterface;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;
use samsonframework\filemanager\FileManagerInterface;

class MetadataBuilderTest extends TestCase
{
    /** @var MetadataBuilder */
    protected $container;

    /** @var ResolverInterface */
    protected $resolver;

    /** @var ContainerInterface */
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
        $this->diContainer = $this->createMock(ContainerInterface::class);

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

    public function testBuild()
    {
        $this->container->build(__DIR__ . '/Container' . uniqid(__CLASS__, true) . '.php');
    }
}
