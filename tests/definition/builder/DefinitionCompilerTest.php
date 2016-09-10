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
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;
use samsonframework\container\tests\TestCaseDefinition;
use samsonframework\generator\ClassGenerator;

class DefinitionCompilerTest extends TestCaseDefinition
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

    /*
     * @return mixed
     */
    public function callMethod($methodName, $obj, $params)
    {
        $method = (new \ReflectionClass(DefinitionCompiler::class))->getMethod($methodName);
        $method->setAccessible(true);
        $result = $method->invokeArgs($obj, $params);
        $method->setAccessible(false);
        return $result;
    }

    public function testGetDependencies()
    {
        $builder = new DefinitionBuilder();
        $builder->addDefinition(Car::class)
            ->defineConstructor()
                ->defineParameter('driver')
                    ->defineDependency(new ClassReference(SlowDriver::class))
                ->end()
            ->end()
        ->end();

        $compiler = new DefinitionCompiler(
            new DefinitionGenerator(new ClassGenerator()),
            $this->getAnalyzer()
        );

        $list = $this->callMethod('getClassDependencies', $compiler, [$builder]);
        static::assertCount(1, $list);
    }

    public function testCreatingDefinitions()
    {
        $builder = new DefinitionBuilder();
        $builder->addDefinition(Car::class)
                ->defineConstructor()
                    ->defineParameter('driver')
                        ->defineDependency(new ClassReference(SlowDriver::class))
                    ->end()
                ->end()
            ->end();

        $compiler = new DefinitionCompiler(
            new DefinitionGenerator(new ClassGenerator()),
            $this->getAnalyzer()
        );

        $list = $this->callMethod('getClassDependencies', $compiler, [$builder]);
        $this->callMethod('generateDefinitions', $compiler, [$builder, $list]);

        static::assertCount(2, $builder->getDefinitionCollection());
    }
}
