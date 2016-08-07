<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 19:03
 */
namespace samsonframework\container\tests;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\Container;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\resolver\AnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;
use samsonframework\filemanager\FileManagerInterface;

class ContainerTest extends TestCase
{
    /** @var Container */
    protected $container;

    /** @var Resolver */
    protected $resolver;

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

        $this->container = new Container($this->fileManager, $this->resolver);
    }

    public function testLoad()
    {
        $this->fileManager->method('scan')->willReturn([
            __DIR__ . '/classes/CarController.php',
            __DIR__ . '/classes/Car.php',
        ]);

        $this->container
            ->loadFromPaths([__DIR__ . '/classes/'])
            ->loadFromClasses([CarController::class, Car::class]);

        static::assertEquals(
            true,
            in_array(CarController::class, $this->getProperty('scopes', $this->container)[Container::SCOPE_CONTROLLER])
        );
        static::assertArrayHasKey(
            CarController::class,
            $this->getProperty('classMetadata', $this->container)
        );
    }
}
