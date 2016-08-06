<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 10:56
 */
namespace samsonframework\di\tests;

use PHPUnit\Framework\TestCase;
use samsonframework\container\resolver\AnnotationResolver;
use samsonframework\container\resolver\Resolver;

class ResolverTest extends TestCase
{
    /** Path to tests cache */
    const P_CACHE = __DIR__.'/cache/';

    /** @var Resolver */
    protected $resolver;

    public function setUp()
    {
        $this->resolver = new AnnotationResolver(self::P_CACHE);
    }

    public function testReslover()
    {
        $this->resolver->resolve(new \ReflectionClass());
    }
}
