<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\scope\ControllerScope;
use samsonframework\container\definition\scope\ServiceScope;
use samsonframework\container\definition\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\PropertyDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\ScopeAlreadyExistsException;
use samsonframework\container\definition\exception\ScopeNotFoundException;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\TestCaseDefinition;


class ClassDefinitionTest extends TestCaseDefinition
{
    public function testSecondConstructorError()
    {
        $class = Car::class;

        $this->expectException(MethodDefinitionAlreadyExistsException::class);

        (new ClassDefinition())
            ->setClassName($class)
            ->defineConstructor()->end()
            ->defineConstructor()->end();
    }

    public function testSecondEqualMethodError()
    {
        $class = Car::class;

        $this->expectException(MethodDefinitionAlreadyExistsException::class);

        (new ClassDefinition())
            ->setClassName($class)
            ->defineMethod('method')->end()
            ->defineMethod('method')->end();
    }

    public function testNameSpace()
    {
        $class = Car::class;
        $namespace = preg_replace('/\\\(.*)$/', '', Car::class);

        /** @var ClassDefinition $definition */
        $definition = (new ClassDefinition())
            ->setClassName($class)
            ->setNameSpace($namespace)
            ->defineProperty('prop')->end();

        static::assertEquals($namespace, $definition->getNameSpace());
    }

    public function testSecondEqualPropertyError()
    {
        $class = Car::class;
        $namespace = preg_replace('/\\\(.*)$/', '', Car::class);

        $this->expectException(PropertyDefinitionAlreadyExistsException::class);

        /** @var ClassDefinition $definition */
        $definition = (new ClassDefinition())
            ->setClassName($class)
            ->setNameSpace($namespace)
            ->defineProperty('prop')->end()
            ->defineProperty('prop')->end();

        static::assertEquals($namespace, $definition->getNameSpace());
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
        static::assertCount(2, $classDefinition->getScopes());
        static::assertTrue($classDefinition->hasScope(ServiceScope::getId()));

        $classDefinition->removeScope(ServiceScope::getId());

        static::assertCount(1, $this->getProperty('scopes', $classDefinition));
    }

    public function testGetNotFoundScope()
    {
        $class = Car::class;
        $classDefinition = (new ClassDefinition())
            ->setClassName($class)
            ->addScope(new ControllerScope());

        $this->expectException(ScopeNotFoundException::class);
        $classDefinition->getScope(ServiceScope::getId());
    }

    public function testExistsScope()
    {
        $class = Car::class;
        $classDefinition = (new ClassDefinition())
            ->setClassName($class)
            ->addScope(new ControllerScope());

        $this->expectException(ScopeAlreadyExistsException::class);
        $classDefinition->addScope(new ControllerScope());
    }

    public function testRemoveNotFoundScope()
    {
        $class = Car::class;
        $classDefinition = (new ClassDefinition())
            ->setClassName($class)
            ->addScope(new ControllerScope());

        $this->expectException(ScopeNotFoundException::class);
        $classDefinition->removeScope(ServiceScope::getId());
    }
}
