<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests;

use samsonframework\container\Builder;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonphp\generator\Generator;

class BuilderTest extends TestCase
{
    /** @var  Builder */
    protected $builder;

    public function setUp()
    {
        $generator = new Generator();
        $fastDriverMetadata = new ClassMetadata();
        $fastDriverMetadata->className = FastDriver::class;
        $fastDriverMetadata->name = 'fastdriver';
        $fastDriverMetadata->scopes[] = 'testscope';

        $constructorMetadata = new MethodMetadata($fastDriverMetadata);
        $constructorMetadata->dependencies['leg'] = Leg::class;
        $fastDriverMetadata->methodsMetadata['__construct'] = $constructorMetadata;

        $legMetadata = new ClassMetadata();
        $legMetadata->className = Leg::class;

        $this->builder = new Builder($generator, [
            FastDriver::class => $fastDriverMetadata,
            Leg::class => $legMetadata,
        ]);
    }

    public function testBuild()
    {
        $containerFile = __DIR__ . '/Container2.php';
        file_put_contents($containerFile, $this->builder->build('Container2', 'DI'));
        require $containerFile;

        //eval($this->builder->build('Container2', 'DI'));
        $container = new \DI\Container2();

        static::assertInstanceOf(FastDriver::class, $container->getFastdriver());
        static::assertInstanceOf(Leg::class, $this->getProperty('leg', $container->getFastdriver()));
    }
}
