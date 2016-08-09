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

class PropertyBuilderTest extends TestCase
{
    /** @var Container */
    protected $container;

    public function setUp()
    {
        $reader = new AnnotationReader();
        $resolver = new AnnotationResolver(
            new AnnotationClassResolver($reader),
            new AnnotationPropertyResolver($reader),
            new AnnotationMethodResolver($reader)
        );
        $fileManager = $this->createMock(FileManagerInterface::class);

        $generator = new Generator();

        $container = new MetadataBuilder(
            $fileManager,
            $resolver,
            $generator
        );

        $containerClass = $container
            ->loadFromClassNames([
                Car::class,
                CarController::class,
                FastDriver::class,
                SlowDriver::class,
                CarServiceWithInterface::class
            ])
            ->build('Container', 'DI');

        file_put_contents(__DIR__ . '/Container.php', $containerClass);
        if (!class_exists(Container::class)) {
            require __DIR__ . '/Container.php';
        }

        $this->container = new Container($generator);
    }

    public function testExistingPropertyByClassName()
    {
        static::assertEquals(
            true,
            $this->container->getSamsonframeworkContainerTestsClassesCarController()->fastDriver instanceof FastDriver
        );
    }

    public function testExistingPropertyByClassNameWithNamespace()
    {
        static::assertEquals(
            true,
            $this->container->getSamsonframeworkContainerTestsClassesCarController()->slowDriver instanceof SlowDriver
        );
    }

    public function testExistingPropertyByClassNameWithTypeHint()
    {
        static::assertEquals(
            true,
            $this->container->getSamsonframeworkContainerTestsClassesCarController()->car instanceof Car
        );
    }
}
