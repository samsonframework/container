<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Ruslan Molodyko
 * Date: 14.08.16
 * Time: 12:10
 */
namespace samsonframework\container;


use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\Scope;
use samsonframework\container\annotation\Service;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\resolver\AnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\di\Container;
use samsonphp\generator\Generator;

/**
 * @Service("container_configuration")
 * @Scope("configuration")
 */
class ContainerConfiguration implements ConfigurationInterface
{
    public function configure(Container $container, array $configData = [])
    {
        $reader = new AnnotationReader();
        $resolver = new AnnotationResolver(
            new AnnotationClassResolver($reader),
            new AnnotationPropertyResolver($reader),
            new AnnotationMethodResolver($reader)
        );

        $generator = new Generator();

        $container = new ContainerBuilder(new LocalFileManager(), $resolver, new Generator());

        $containerClass = $container
            ->loadFromClassNames([
                Read::class,
                Logger::class
            ]);

        foreach ($container->classMetadata as $className => $classMetadata) {
            $serviceName = $classMetadata->name;
            if (array_key_exists($serviceName, $configData)) {
                foreach ($configData[$serviceName] as $propertyName => $dependency) {
                    if (array_key_exists($propertyName, $classMetadata->propertiesMetadata)) {
                        $classMetadata->propertiesMetadata[$propertyName]->dependency = ltrim($dependency, '\\');
                    }
                }
            }
        }

        $containerClass = $containerClass->build('Container1', 'DI');

        $className = 'Container1.php';
        file_put_contents(__DIR__ . '/' . $className, $containerClass);
        if (!class_exists(Container1::class, false)) {
            require_once __DIR__ . '/' . $className;
        }

        return new \DI\Container1($generator);
    }
}