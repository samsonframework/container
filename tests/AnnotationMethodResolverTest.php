<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:59
 */
namespace samsonframework\container\tests;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\Inject;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\AnnotationMethodResolver;
use samsonframework\container\tests\classes as tests;

class AnnotationMethodResolverTest extends TestCase
{
    /** @var AnnotationMethodResolver */
    protected $resolver;

    /** @var ClassMetadata */
    protected $classMetadata;

    public function setUp()
    {
        /** @var ClassMetadata $methodResolver */
        $this->classMetadata = new ClassMetadata();

        $this->resolver = new AnnotationMethodResolver(new AnnotationReader(), $this->classMetadata);
    }

    public function testInjectResolve()
    {
        new Inject(['']);

        $reflectionClass = new \ReflectionClass(tests\CarController::class);
        $this->classMetadata->nameSpace = ($reflectionClass->getNamespaceName());

        $classMetadata = $this->resolver->resolve($reflectionClass, $this->classMetadata);
        $methodMetadata = $classMetadata->methodsMetadata;

        static::assertEquals(tests\Car::class, $methodMetadata['car']->injectable);
        static::assertEquals(tests\FastDriver::class, $methodMetadata['fastDriver']->injectable);
        static::assertEquals(tests\SlowDriver::class, $methodMetadata['slowDriver']->injectable);
    }
}
