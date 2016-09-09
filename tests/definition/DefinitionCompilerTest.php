<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\DefinitionBuilder;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\TestCaseDefinition;


class DefinitionCompilerTest extends TestCaseDefinition
{
    public function testAddDefinition()
    {
        $definitionBuilder = new DefinitionBuilder();
//        $definitionCompiler = new DefinitionCompiler($definitionBuilder);

        $definitionBuilder->addDefinition(Car::class)
            ->defineConstructor()
                ->defineParameter('driver')
                    ->defineDependency(new ClassReference(SlowDriver::class))
                ->end()
            ->end()
            ->defineProperty('driver')
                ->defineDependency(new ClassReference(FastDriver::class))
            ->end();

        $definitionBuilder->analyze();
//        $container = $definitionCompiler->compile(ContainerInterface $parentContainer);
    }
}
