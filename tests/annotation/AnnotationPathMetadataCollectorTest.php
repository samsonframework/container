<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 13:15
 */
namespace samsonframework\container\tests\annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\AnnotationClassResolver;
use samsonframework\container\annotation\AnnotationMethodResolver;
use samsonframework\container\annotation\AnnotationPropertyResolver;
use samsonframework\container\annotation\AnnotationResolver;
use samsonframework\container\annotation\Service;
use samsonframework\container\AnnotationPathMetadataCollector;
use samsonframework\container\Builder;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\TestCase;
use samsonframework\localfilemanager\LocalFileManager;

class AnnotationPathMetadataCollectorTest extends TestCase
{
    /** @var AnnotationPathMetadataCollector */
    protected $annotationCollector;

    public function setUp()
    {
        $reader = new AnnotationReader();

        $resolver = new AnnotationResolver(
            new AnnotationClassResolver($reader),
            new AnnotationPropertyResolver($reader),
            new AnnotationMethodResolver($reader)
        );

        $this->annotationCollector = new AnnotationPathMetadataCollector($resolver, new LocalFileManager());
    }

    public function testCollect()
    {
        new Service(['value' => 'test']);

        /** @var ClassMetadata[] $classesMetadata */
        $classesMetadata = $this->annotationCollector->collect([dirname(__DIR__, 1) . '/classes']);

        static::assertEquals(CarController::class, $classesMetadata[CarController::class]->className);
        static::assertTrue(in_array(Builder::SCOPE_CONTROLLER, $classesMetadata[CarController::class]->scopes, true));
    }
}
