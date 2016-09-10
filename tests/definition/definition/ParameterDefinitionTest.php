<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\ParameterDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\TestCaseDefinition;


class ParameterDefinitionTest extends TestCaseDefinition
{
    public function testParameter()
    {
        $class = Car::class;
        $classDefinition = (new ClassDefinition())->setClassName($class);
        $methodDefinition = (new MethodDefinition($classDefinition))->setMethodName('__construct');
        $parameterDefinition = (new ParameterDefinition($methodDefinition))
            ->defineDependency(new ClassReference(FastDriver::class));

        static::assertInstanceOf(ClassReference::class, $parameterDefinition->getDependency());
    }
}
