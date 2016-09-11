<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\ContainerInterface;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionClassAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionMethodAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionParameterAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionPropertyAnalyzer;
use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\definition\builder\DefinitionCompiler;
use samsonframework\container\definition\builder\DefinitionGenerator;
use samsonframework\container\definition\parameter\ParameterBuilder;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\IntegerReference;
use samsonframework\container\definition\reference\NullReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\tests\classes\annotation\ProductClass;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;
use samsonframework\container\tests\TestCaseDefinition;
use samsonframework\generator\ClassGenerator;

class DefinitionGeneratorTest extends TestCaseDefinition
{

    public function getAnalyzer()
    {
        return new DefinitionAnalyzer(
            [new ReflectionClassAnalyzer()],
            [new ReflectionMethodAnalyzer()],
            [new ReflectionPropertyAnalyzer()],
            [new ReflectionParameterAnalyzer()]
        );
    }

    // This test if wrong but i can't test generated code
    public function testGetCode()
    {
        $definitionBuilder = new DefinitionBuilder(new ParameterBuilder());

        $definitionBuilder
//            ->addDefinition(SlowDriver::class)->end()
            ->addDefinition(Car::class)
                ->defineIsSingleton()
                ->defineConstructor()
                    ->defineParameter('driver')
                        ->defineDependency(new ClassReference(SlowDriver::class))
                    ->end()
                ->end()
                ->defineProperty('driver')
                    ->defineDependency(new ClassReference(SlowDriver::class))
                ->end()
            ->end()
            ->addDefinition(WheelController::class)
                ->defineConstructor()
                    ->defineParameter('fastDriver')
                        ->defineDependency(new ClassReference(SlowDriver::class))
                    ->end()
                    ->defineParameter('slowDriver')
                        ->defineDependency(new ClassReference(SlowDriver::class))
                    ->end()
                    ->defineParameter('car')
                        ->defineDependency(new ClassReference(Car::class))
                    ->end()
                    ->defineParameter('params')
                        ->defineDependency((new CollectionReference([
                            new CollectionItem(new ConstantReference('PHP_VERSION'), new IntegerReference(1)),
                            'sdf' => 33,
                            'sdf1' => new ConstantReference('PHP_MAJOR_VERSION'),
                            'sdf2' => new StringReference('value'),
                            3333 => 5555
                        ]))->addItem(CollectionItem::create(3333, new ConstantReference('PHP_MINOR_VERSION')))
                        ->addItem(CollectionItem::create(22, 'sdf'))
                        ->addItem(CollectionItem::create('sdf', 'dsddd')))
                    ->end()
                    ->defineParameter('id')
                        ->defineDependency(new ConstantReference('PHP_RELEASE_VERSION'))
                    ->end()
                ->end()
                ->defineMethod('setLeg')
                    ->defineParameter('leg')
                        ->defineDependency(new ClassReference(Leg::class))
                    ->end()
                ->end()
                ->defineProperty('car')
                    ->defineDependency(new ClassReference(Car::class))
                ->end()
            ->end()
            ->addDefinition(ProductClass::class)
                ->defineConstructor()->end()
            ->end()
        ;

        $compiler = new DefinitionCompiler(
            new DefinitionGenerator(new ClassGenerator()),
            $this->getAnalyzer()
        );

        $namespace = (new \ReflectionClass(self::class))->getNamespaceName();
        /** @var ContainerInterface $container */
        $container = $compiler->compile($definitionBuilder, 'ContainerGeneratorTest', $namespace, __DIR__ . '/../../generated');
        static::assertInstanceOf(WheelController::class, $container->get(WheelController::class));
    }
}
