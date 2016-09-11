<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\parameter\exception\ParameterAlreadyExistsException;
use samsonframework\container\definition\parameter\exception\ParameterNotFoundException;
use samsonframework\container\definition\parameter\ParameterBuilder;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\FloatReference;
use samsonframework\container\definition\reference\IntegerReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\tests\TestCaseDefinition;


class ParameterBuilderTest extends TestCaseDefinition
{
    public function testDefineParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'));

        static::assertCount(1, $parameterBuilder->getParameterCollection());
        static::assertInstanceOf(StringReference::class, $parameterBuilder->get('param1'));
    }

    public function testMultipleDefineParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2))
            ->defineParameter('param3', new IntegerReference(3));

        static::assertCount(3, $parameterBuilder->getParameterCollection());
        static::assertInstanceOf(StringReference::class, $parameterBuilder->get('param1'));
        static::assertInstanceOf(FloatReference::class, $parameterBuilder->get('param2'));
        static::assertInstanceOf(IntegerReference::class, $parameterBuilder->get('param3'));
    }

    public function testChangeParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'));

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
            ->add('param3', new IntegerReference(3));

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
            ->defineParameter('param3', new IntegerReference(3));

        static::assertTrue($parameterBuilder->has('param1'));
    }

    public function testRemoveParameter()
    {
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2))
            ->defineParameter('param3', new IntegerReference(3));

        static::assertCount(3, $parameterBuilder->getParameterCollection());
        static::assertTrue($parameterBuilder->has('param1'));

        $parameterBuilder->remove('param1');

        static::assertCount(2, $parameterBuilder->getParameterCollection());
        static::assertFalse($parameterBuilder->has('param1'));
    }

    public function testDefineParameterTwice()
    {
        $this->expectException(ParameterAlreadyExistsException::class);

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param1', new FloatReference(2.2));
    }

    public function testAddParameterTwice()
    {
        $this->expectException(ParameterAlreadyExistsException::class);

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->add('param1', new StringReference('value1'))
            ->add('param1', new FloatReference(2.2));
    }

    public function testRemoveMissing()
    {
        $this->expectException(ParameterNotFoundException::class);

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2));

        $parameterBuilder->remove('param1');
        $parameterBuilder->remove('param1');
    }

    public function testGetMissing()
    {
        $this->expectException(ParameterNotFoundException::class);

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2));

        $parameterBuilder->get('missing_parameter');
    }

    public function testChangeMissingError()
    {
        $this->expectException(ParameterNotFoundException::class);

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder
            ->defineParameter('param1', new StringReference('value1'))
            ->defineParameter('param2', new FloatReference(2.2));

        $parameterBuilder->changeParameter('param_missing', new CollectionReference());
    }
}
