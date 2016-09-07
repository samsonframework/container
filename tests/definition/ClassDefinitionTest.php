<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\DefinitionBuilder;
use samsonframework\container\definition\ParameterDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\reference\ResourceReference;
use samsonframework\container\definition\reference\ServiceReference;
use samsonframework\container\definition\scope\ControllerScope;
use samsonframework\container\definition\scope\ServiceScope;
use samsonframework\container\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\exception\ParentDefinitionNotFoundException;
use samsonframework\container\exception\PropertyDefinitionAlreadyExistsException;
use samsonframework\container\exception\ScopeAlreadyExistsException;
use samsonframework\container\exception\ScopeNotFoundException;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\classes\DriverInterface;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;
use samsonframework\container\tests\TestCaseDefinition;


class ClassDefinitionTest extends TestCaseDefinition
{
    public function testSecondConstructorError()
    {
        $class = Car::class;

        static::expectException(MethodDefinitionAlreadyExistsException::class);

        (new ClassDefinition())
            ->setClassName($class)
            ->defineConstructor()->end()
            ->defineConstructor()->end();
    }

    public function testSecondEqualMethodError()
    {
        $class = Car::class;

        static::expectException(MethodDefinitionAlreadyExistsException::class);

        (new ClassDefinition())
            ->setClassName($class)
            ->defineMethod('method')->end()
            ->defineMethod('method')->end();
    }


    public function testSecondEqualPropertyError()
    {
        $class = Car::class;

        $this->expectException(PropertyDefinitionAlreadyExistsException::class);

        (new ClassDefinition())
            ->setClassName($class)
            ->defineProperty('prop')->end()
            ->defineProperty('prop')->end();
    }

    public function testScope()
    {
        $class = Car::class;
        $classDefinition = (new ClassDefinition())
            ->setClassName($class)
            ->addScope(new ControllerScope())
            ->addScope(new ServiceScope());

        static::assertEquals('service', ServiceScope::getId());
        static::assertInstanceOf(ServiceScope::class, $classDefinition->getScope(ServiceScope::getId()));
        static::assertCount(2, $this->getProperty('scopes', $classDefinition));

        $classDefinition->removeScope(ServiceScope::getId());

        static::assertCount(1, $this->getProperty('scopes', $classDefinition));

        $this->expectException(ScopeNotFoundException::class);
        $classDefinition->getScope(ServiceScope::getId());

        $this->expectException(ScopeAlreadyExistsException::class);

        $classDefinition->addScope(new ServiceScope());
    }
}
