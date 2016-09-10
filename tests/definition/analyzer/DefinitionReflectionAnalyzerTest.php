<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\analyzer;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\exception\ParameterNotFoundException;
use samsonframework\container\definition\analyzer\reflection\ReflectionClassAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionMethodAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionParameterAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionPropertyAnalyzer;
use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;
use samsonframework\container\tests\TestCaseDefinition;


class DefinitionReflectionAnalyzerTest extends TestCaseDefinition
{
    public function callAnalyze(DefinitionBuilder $definitionBuilder)
    {
        $method = (new \ReflectionClass(DefinitionAnalyzer::class))->getMethod('analyze');
        $method->setAccessible(true);
        $method->invoke(new DefinitionAnalyzer(
            [new ReflectionClassAnalyzer()],
            [new ReflectionMethodAnalyzer()],
            [new ReflectionPropertyAnalyzer()],
            [new ReflectionParameterAnalyzer()]
        ), $definitionBuilder);
        $method->setAccessible(false);
    }

    public function testAddDefinition()
    {
        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder->addDefinition(Car::class)
            ->defineConstructor()
                ->defineParameter('driver')
                    ->defineDependency(new ClassReference(SlowDriver::class))
                ->end()
            ->end()
            ->defineProperty('driver')
                ->defineDependency(new ClassReference(FastDriver::class))
            ->end();

        $this->callAnalyze($definitionBuilder);

        $classDefinition = $this->getClassDefinition($definitionBuilder, Car::class);
        $methodDefinition = $this->getMethodDefinition($definitionBuilder, Car::class, '__construct');
        $parameterDefinition = $this->getParameterDefinition($definitionBuilder, Car::class, '__construct', 'driver');
        $propertyDefinition = $this->getPropertyDefinition($definitionBuilder, Car::class, 'driver');

        static::assertEquals('samsonframework\container\tests\classes', $classDefinition->getNameSpace());

        static::assertTrue($methodDefinition->isPublic());
        static::assertEquals(2550145280, $methodDefinition->getModifiers());

        static::assertEquals(512, $propertyDefinition->getModifiers());
        static::assertFalse($propertyDefinition->isPublic());

        static::assertFalse($parameterDefinition->isOptional());
        static::assertEquals('samsonframework\container\tests\classes\DriverInterface', (string)$parameterDefinition->getTypeHint());
        static::assertEquals('driver', $parameterDefinition->getParameterName());
        static::assertNull($parameterDefinition->getValue());
    }

    public function testPropertyDefaultValue()
    {
        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder->addDefinition(WheelController::class)
            ->defineMethod('setDriver')
                ->defineParameter('leg')->end()
            ->end();

        $this->callAnalyze($definitionBuilder);

        $parameterDefinition = $this->getParameterDefinition($definitionBuilder, WheelController::class, 'setDriver', 'leg');
        static::assertEquals('leg', $parameterDefinition->getValue());
    }

    public function testWrongClassName()
    {
        $this->expectException(\ReflectionException::class);

        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder->addDefinition('sdf');
        $this->callAnalyze($definitionBuilder);
    }

//    public function testWrongMethodName()
//    {
//        $this->expectException(\ReflectionException::class);
//
//        $definitionBuilder = new DefinitionBuilder();
//
//        $definitionBuilder->addDefinition(Car::class)
//            ->defineMethod('sdf')->end();
//        $this->callAnalyze($definitionBuilder);
//    }

//    public function testWrongPropertyName()
//    {
//        $this->expectException(\ReflectionException::class);
//
//        $definitionBuilder = new DefinitionBuilder();
//
//        $definitionBuilder->addDefinition(Car::class)
//            ->defineProperty('sdf')->end();
//        $this->callAnalyze($definitionBuilder);
//    }

    public function testWrongParameterName()
    {
        $this->expectException(ParameterNotFoundException::class);

        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder->addDefinition(Car::class)
            ->defineConstructor()
                ->defineParameter('sdfsdf')->end()
            ->end();
        $this->callAnalyze($definitionBuilder);
    }
}
