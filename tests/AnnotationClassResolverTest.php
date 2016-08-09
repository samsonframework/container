<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:59
 */
namespace samsonframework\container\tests;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\annotation\Controller;
use samsonframework\container\annotation\Route;
use samsonframework\container\annotation\Scope;
use samsonframework\container\ContainerBuilder;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\tests\classes as tests;

class AnnotationClassResolverTest extends TestCase
{
    /** @var AnnotationClassResolver */
    protected $resolver;

    /** @var ClassMetadata */
    protected $classMetadata;

    public function setUp()
    {
        /** @var ClassMetadata $methodResolver */
        $this->classMetadata = new ClassMetadata();

        $this->resolver = new AnnotationClassResolver(new AnnotationReader(), $this->classMetadata);
    }

    public function testResolve()
    {
        new Route(['value' => '/test/']);
        new Controller();
        new Scope(['value' => 'test']);

        $reflectionClass = new \ReflectionClass(tests\CarController::class);
        $this->classMetadata->className = $reflectionClass->getName();
        $this->classMetadata->nameSpace = $reflectionClass->getNamespaceName();

        $classMetadata = $this->resolver->resolve($reflectionClass, $this->classMetadata);

        static::assertEquals(true, in_array('cars', $classMetadata->scopes, true));
        static::assertEquals(true, in_array(ContainerBuilder::SCOPE_CONTROLLER, $classMetadata->scopes, true));
    }
}
