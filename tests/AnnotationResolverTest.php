<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 10:56
 */
namespace samsonframework\di\tests;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use samsonframework\container\annotation\Controller;
use samsonframework\container\annotation\Inject;
use samsonframework\container\resolver\AbstractAnnotationMethodResolver;
use samsonframework\container\resolver\AbstractAnnotationPropertyResolver;
use samsonframework\container\resolver\AnnotationClassResolver;
use samsonframework\container\resolver\Resolver;
use samsonframework\container\tests\classes as tests;

class AnnotationResolverTest extends TestCase
{
    /** @var AnnotationClassResolver */
    protected $resolver;

    public function setUp()
    {
        /** @var Resolver $propertyResolver */
        $propertyResolver = $this->createMock(AbstractAnnotationPropertyResolver::class);
        /** @var Resolver $methodResolver */
        $methodResolver = $this->createMock(AbstractAnnotationMethodResolver::class);

        $this->resolver = new AnnotationClassResolver(new AnnotationReader(), $propertyResolver, $methodResolver);
    }

    public function testResolve()
    {
        // Autoload annotations
        // TODO: Why doctrine not loading them?
        new Controller();
        new Inject([]);

        $identifier = 'testID';
        //$metadata = $this->resolver->resolve(new \ReflectionClass(tests\CarController::class), $identifier);

        //static::assertEquals(['Car'], $metadata->dependencies);
    }
}
