<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 10:56
 */
namespace samsonframework\di\tests;

use PHPUnit\Framework\TestCase;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\resolver\AnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\container\resolver\Resolver;
use samsonframework\container\tests\classes as tests;

class AnnotationResolverTest extends TestCase
{
    /** @var AnnotationClassResolver */
    protected $resolver;

    public function setUp()
    {
        /** @var Resolver $classResolver */
        $classResolver = $this->createMock(AnnotationClassResolver::class);
        /** @var Resolver $propertyResolver */
        $propertyResolver = $this->createMock(AnnotationPropertyResolver::class);
        /** @var Resolver $methodResolver */
        $methodResolver = $this->createMock(AnnotationMethodResolver::class);

        $this->resolver = new AnnotationResolver($classResolver, $propertyResolver, $methodResolver);
    }

    public function testResolve()
    {
        $classMetadata = $this->resolver->resolve(new \ReflectionClass(tests\CarController::class));
        static::assertEquals(true, $classMetadata instanceof ClassMetadata);
    }
}
