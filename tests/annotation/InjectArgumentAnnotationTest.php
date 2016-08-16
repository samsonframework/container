<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\InjectArgument;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\ParameterMetadata;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\TestCase;

class InjectArgumentAnnotationTest extends TestCase
{
    public function testMethodToMetadata()
    {
        $argumentName = 'argumentName';
        $inject = new InjectArgument([$argumentName => CarController::class]);
        $methodMetadata = new MethodMetadata(new ClassMetadata());
        $methodMetadata->parametersMetadata[$argumentName] = new ParameterMetadata($methodMetadata->classMetadata, $methodMetadata);
        $inject->toMethodMetadata($methodMetadata);
        static::assertEquals(true, in_array(CarController::class, $methodMetadata->dependencies, true));
    }

    public function testAnnotationWithoutArgumentName()
    {
        $this->expectException(\InvalidArgumentException::class);

        $inject = new InjectArgument(['' => CarController::class]);
        $methodMetadata = new MethodMetadata(new ClassMetadata());
        $methodMetadata->parametersMetadata['value'] = new ParameterMetadata($methodMetadata->classMetadata, $methodMetadata);
        $inject->toMethodMetadata($methodMetadata);
    }

    public function testAnnotationArgumentNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $inject = new InjectArgument(['notValue' => CarController::class]);
        $methodMetadata = new MethodMetadata(new ClassMetadata());
        $methodMetadata->parametersMetadata['value'] = new ParameterMetadata($methodMetadata->classMetadata, $methodMetadata);
        $inject->toMethodMetadata($methodMetadata);
    }

    public function testAnnotationWithoutArgumentType()
    {
        new InjectArgument(['field' => 'type']);
        $this->expectException(\InvalidArgumentException::class);
        $argumentName = 'argumentName';
        $inject = new InjectArgument([$argumentName => '']);
        $methodMetadata = new MethodMetadata(new ClassMetadata());
        $methodMetadata->parametersMetadata[$argumentName] = new ParameterMetadata($methodMetadata->classMetadata, $methodMetadata);
        $inject->toMethodMetadata($methodMetadata);
    }
}
