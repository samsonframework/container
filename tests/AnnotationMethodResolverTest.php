<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:59
 */
namespace samsonframework\container\tests;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\AnnotationMethodResolver;
use samsonframework\container\annotation\InjectArgument;
use samsonframework\container\annotation\Route;
use samsonframework\container\metadata\ClassMetadata;
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

    public function testResolve()
    {
        new InjectArgument(['field' => 'type']);
        new Route(['value' => '/test/']);

        $reflectionClass = new \ReflectionClass(tests\CarController::class);
        $this->classMetadata->className = $reflectionClass->getName();
        $this->classMetadata->nameSpace = $reflectionClass->getNamespaceName();

        $classMetadata = $this->resolver->resolve($reflectionClass, $this->classMetadata);
        $methodMetadata = $classMetadata->methodsMetadata;

        static::assertArrayHasKey('showAction', $methodMetadata);
        static::assertArrayHasKey('fastDriver', $methodMetadata['showAction']->parametersMetadata);
        static::assertArrayHasKey('slowDriver', $methodMetadata['showAction']->parametersMetadata);
        static::assertEquals(true, in_array(tests\FastDriver::class, $methodMetadata['showAction']->dependencies, true));
        static::assertEquals(true, in_array(tests\SlowDriver::class, $methodMetadata['showAction']->dependencies, true));
    }
}
