<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 10:56
 */
namespace samsonframework\di\tests;

use PHPUnit\Framework\TestCase;
use samsonframework\container\annotation\Inject;
use samsonframework\container\Container;
use \samsonframework\container\resolver\AnnotationResolver;
use \samsonframework\container\tests\classes as tests;
use \samsonframework\container\annotation\Controller;

class AnnotationResolverTest extends TestCase
{
    /** Path to tests cache */
    const P_CACHE = __DIR__.'/cache/';

    /** @var AnnotationResolver */
    protected $resolver;

    public function setUp()
    {
        $this->resolver = new AnnotationResolver(self::P_CACHE);
    }

    public function testResolve()
    {
        // Autoload annotations
        // TODO: Why doctrine not loading them?
        new Controller();
        new Inject('');

        $identifier = 'testID';
        $metadata = $this->resolver->resolve(new \ReflectionClass(tests\CarController::class), $identifier);

        static::assertEquals(false, $metadata->autowire);
        static::assertEquals($identifier, $metadata->internalId);
        static::assertEquals(true, in_array(Container::SCOPE_CONTROLLER, $metadata->scopes));
    }
}
