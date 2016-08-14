<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Ruslan Molodyko
 * Date: 14.08.16
 * Time: 12:10
 */
namespace samsonframework\container;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\resolver\AnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarService;
use samsonframework\container\tests\TestCase;
use samsonframework\filemanager\FileManagerInterface;
use samsonphp\generator\Generator;

class ContainerConfigurationTest extends TestCase
{
    public function testConfigure()
    {
        $reader = new AnnotationReader();
        $resolver = new AnnotationResolver(
            new AnnotationClassResolver($reader),
            new AnnotationPropertyResolver($reader),
            new AnnotationMethodResolver($reader)
        );

        $generator = new Generator();

        $container = new ContainerBuilder($this->createMock(FileManagerInterface::class), $resolver, new Generator());

        $containerClass = $container
            ->loadFromClassNames([
                Car::class,
                CarService::class
            ]);

        $configData = [];

        foreach ($this->getProperty('classMetadata', $container) as $className => $classMetadata) {
            $serviceName = $classMetadata->name;
            if (array_key_exists($serviceName, $configData)) {
                foreach ($configData[$serviceName] as $propertyName => $dependency) {
                    if (array_key_exists($propertyName, $classMetadata->propertiesMetadata)) {
                        $classMetadata->propertiesMetadata[$propertyName]->dependency = ltrim($dependency, '\\');
                    }
                }
            }
        }

        $containerClass = $containerClass->build('Container', 'DI');

        $path = __DIR__ . '/Container2.php';
        file_put_contents(__DIR__ . $path, $containerClass);

//        if (!class_exists(Container::class, false)) {
//            require_once __DIR__ . 'ContainerConfiguration.php/' . $className;
//        }

        //return new \DI\Container($generator);

        //$container = $containerConfig->configure(null, $configData);

        static::assertInstanceOf(Logger::class, $container->getReader()->logger);
    }
}