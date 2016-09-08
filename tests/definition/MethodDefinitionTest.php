<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\exception\ParameterDefinitionAlreadyExistsException;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\TestCaseDefinition;


class MethodDefinitionTest extends TestCaseDefinition
{
    public function testSecondConstructorError()
    {
        $class = Car::class;
        $classDefinition = (new ClassDefinition())->setClassName($class);
        $methodDefinition = (new MethodDefinition($classDefinition))
            ->setMethodName('__construct')
            ->defineParameter('driver')
                ->defineDependency(new ClassReference(FastDriver::class))
            ->end()
        ;

        static::assertInstanceOf(ClassReference::class, $this->getProperty('parametersCollection', $methodDefinition)['driver']->getDependency());
    }

    public function testEqualsMethods()
    {
        $this->expectException(ParameterDefinitionAlreadyExistsException::class);

        $class = Car::class;
        $classDefinition = (new ClassDefinition())->setClassName($class);
        (new MethodDefinition($classDefinition))
            ->setMethodName('__construct')
            ->defineParameter('driver')->end()
            ->defineParameter('driver')->end()
        ;
    }
}
