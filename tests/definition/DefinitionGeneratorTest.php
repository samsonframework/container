<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\DefinitionBuilder;
use samsonframework\container\definition\DefinitionCompiler;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;
use samsonframework\container\tests\TestCaseDefinition;


class DefinitionGeneratorTest extends TestCaseDefinition
{
    public function testGetCode()
    {
        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder
            ->addDefinition(Car::class)
                ->defineIsSingleton()
                ->defineConstructor()
                    ->defineParameter('driver')
                        ->defineDependency(new ClassReference(SlowDriver::class))
                    ->end()
                ->end()
                ->defineProperty('driver')
                    ->defineDependency(new ClassReference(FastDriver::class))
                ->end()
            ->end()
            ->addDefinition(WheelController::class)
                ->defineConstructor()
                    ->defineParameter('fastDriver')
                        ->defineDependency(new ClassReference(FastDriver::class))
                    ->end()
                    ->defineParameter('slowDriver')
                        ->defineDependency(new ClassReference(SlowDriver::class))
                    ->end()
                    ->defineParameter('car')
                        ->defineDependency(new ClassReference(Car::class))
                    ->end()
                    ->defineParameter('params')
                        ->defineDependency((new CollectionReference([
                            new CollectionItem(new ConstantReference('\AMQP_EX_TYPE_DIRECT'), 1),
                            'sdf' => 33,
                            'sdf1' => new ConstantReference('\AMQP_EX_TYPE_DIRECT'),
                            'sdf2' => new StringReference('value'),
                            3333 => 5555
                        ]))->addItem(3333, new ConstantReference('\AMQP_EX_TYPE_DIRECT'))
                        ->addItem('sdf')
                        ->addItem('sdf', 'dsddd'))
                    ->end()
                    ->defineParameter('id')
                        ->defineDependency(new ConstantReference('\AMQP_EX_TYPE_DIRECT'))
                    ->end()
                ->end()
                ->defineProperty('car')
                    ->defineDependency(new ClassReference(Car::class))
                ->end()
            ->end()
        ;

        $compiler = new DefinitionCompiler();

        $namespace = (new \ReflectionClass(self::class))->getNamespaceName();
        $container = $compiler->compile($definitionBuilder, 'Container', $namespace, __DIR__);
        $container->get(Car::class);
    }
}
