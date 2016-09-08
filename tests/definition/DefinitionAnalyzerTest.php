<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\DefinitionAnalyzer;
use samsonframework\container\definition\DefinitionBuilder;
use samsonframework\container\definition\exception\ParameterNotFoundException;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;
use samsonframework\container\tests\TestCaseDefinition;


class DefinitionAnalyzerTest extends TestCaseDefinition
{
    public function testAddDefinition()
    {
        $definitionBuilder = new DefinitionBuilder();
        $definitionAnalyzer = new DefinitionAnalyzer($definitionBuilder);

        $definitionBuilder->addDefinition(Car::class)
            ->defineConstructor()
                ->defineParameter('driver')
                    ->defineDependency(new ClassReference(SlowDriver::class))
                ->end()
            ->end()
            ->defineProperty('driver')
                ->defineDependency(new ClassReference(FastDriver::class))
            ->end();

        $definitionAnalyzer->analyze();

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
        $definitionAnalyzer = new DefinitionAnalyzer($definitionBuilder);

        $definitionBuilder->addDefinition(WheelController::class)
            ->defineMethod('setDriver')
                ->defineParameter('leg')->end()
            ->end();

        $definitionAnalyzer->analyze();

        $parameterDefinition = $this->getParameterDefinition($definitionBuilder, WheelController::class, 'setDriver', 'leg');
        static::assertEquals('leg', $parameterDefinition->getValue());
    }

    public function testWrongClassName()
    {
        $this->expectException(\ReflectionException::class);

        $definitionBuilder = new DefinitionBuilder();
        $definitionAnalyzer = new DefinitionAnalyzer($definitionBuilder);

        $definitionBuilder->addDefinition('sdf');
        $definitionAnalyzer->analyze();
    }

    public function testWrongMethodName()
    {
        $this->expectException(\ReflectionException::class);

        $definitionBuilder = new DefinitionBuilder();
        $definitionAnalyzer = new DefinitionAnalyzer($definitionBuilder);

        $definitionBuilder->addDefinition(Car::class)
            ->defineMethod('sdf')->end();
        $definitionAnalyzer->analyze();
    }

    public function testWrongPropertyName()
    {
        $this->expectException(\ReflectionException::class);

        $definitionBuilder = new DefinitionBuilder();
        $definitionAnalyzer = new DefinitionAnalyzer($definitionBuilder);

        $definitionBuilder->addDefinition(Car::class)
            ->defineProperty('sdf')->end();
        $definitionAnalyzer->analyze();
    }

    public function testWrongParameterName()
    {
        $this->expectException(ParameterNotFoundException::class);

        $definitionBuilder = new DefinitionBuilder();
        $definitionAnalyzer = new DefinitionAnalyzer($definitionBuilder);

        $definitionBuilder->addDefinition(Car::class)
            ->defineConstructor()
                ->defineParameter('sdfsdf')->end()
            ->end();
        $definitionAnalyzer->analyze();
    }
}
