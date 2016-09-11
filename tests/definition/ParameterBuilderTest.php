<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\parameter\ParameterBuilder;
use samsonframework\container\definition\reference\BoolReference;
use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\FloatReference;
use samsonframework\container\definition\reference\IntegerReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\definition\scope\ControllerScope;
use samsonframework\container\definition\scope\ServiceScope;
use samsonframework\container\tests\TestCaseDefinition;


class ParameterBuilderTest extends TestCaseDefinition
{
    public function testDefineParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
        ->end();

        static::assertCount(1, $parameterBuilder->getParameterCollection());
        static::assertInstanceOf(StringReference::class, $parameterBuilder->get('param1'));
    }

    public function testMultipleDefineParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2))
            ->defineParameter('param3', new IntegerReference(3))
        ->end();

        static::assertCount(3, $parameterBuilder->getParameterCollection());
        static::assertInstanceOf(StringReference::class, $parameterBuilder->get('param1'));
        static::assertInstanceOf(FloatReference::class, $parameterBuilder->get('param2'));
        static::assertInstanceOf(IntegerReference::class, $parameterBuilder->get('param3'));
    }

    public function testChangeParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->end();

        static::assertCount(1, $parameterBuilder->getParameterCollection());
        static::assertInstanceOf(StringReference::class, $parameterBuilder->get('param1'));

        $parameterBuilder->changeParameter('param1', new FloatReference(33.3));
        static::assertInstanceOf(FloatReference::class, $parameterBuilder->get('param1'));
    }

    public function testAddParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->add('param1', new StringReference('value1'))
            ->add('param2', new FloatReference(2.2))
            ->add('param3', new IntegerReference(3))
        ->end();

        static::assertCount(3, $parameterBuilder->getParameterCollection());
        static::assertInstanceOf(StringReference::class, $parameterBuilder->get('param1'));
        static::assertInstanceOf(FloatReference::class, $parameterBuilder->get('param2'));
        static::assertInstanceOf(IntegerReference::class, $parameterBuilder->get('param3'));
    }

    public function testHasParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2))
            ->defineParameter('param3', new IntegerReference(3))
        ->end();

        static::assertTrue($parameterBuilder->has('param1'));
    }

    public function testRemoveParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2))
            ->defineParameter('param3', new IntegerReference(3))
        ->end();

        static::assertCount(3, $parameterBuilder->getCollection());
        static::assertTrue($parameterBuilder->has('param1'));

        $parameterBuilder->remove('param1');

        static::assertCount(2, $parameterBuilder->getCollection());
        static::assertFalse($parameterBuilder->has('param1'));
    }

    public function testDefineParameterTwice()
    {
        $this->expectException();

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param1', new FloatReference(2.2))
        ->end();
    }

    public function testAddParameterTwice()
    {
        $this->expectException();

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->add('param1', new StringReference('value1'))
            ->add('param1', new FloatReference(2.2))
        ->end();
    }

    public function testRemoveMissing()
    {
        $this->expectException();

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2))
        ->end();

        $parameterBuilder->remove('param1');
        $parameterBuilder->remove('param1');
    }

    public function testGetMissing()
    {
        $this->expectException();

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2))
            ->end();

        $parameterBuilder->get('missing_parameter');
    }

    public function testChangeMissingError()
    {
        $this->expectException();

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2))
            ->end();

        $parameterBuilder->get('missing_parameter');
    }
}
