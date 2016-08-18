<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests;

use samsonframework\container\Builder;
use samsonframework\container\metadata\ClassMetadata;
use samsonphp\generator\Generator;

class BuilderTest extends TestCase
{
    /** @var  Builder */
    protected $builder;

    public function setUp()
    {
        $generator = new Generator();
        $classMetadata = new ClassMetadata();

        $this->builder = new Builder($generator, [$classMetadata]);
    }

    public function testBuild()
    {

    }
}
