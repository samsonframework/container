<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\TestCaseDefinition;


class PropertyDefinitionTest extends TestCaseDefinition
{
    public function testProperty()
    {
        $class = Car::class;
        $classDefinition = (new ClassDefinition())->setClassName($class);
        $propertyDefinition = (new PropertyDefinition($classDefinition))
            ->setPropertyName('driver')
            ->defineDependency(new ClassReference($class));

        static::assertInstanceOf(ClassReference::class, $propertyDefinition->getDependency());
        static::assertEquals('\\' . Car::class, $propertyDefinition->getDependency()->getClassName());
    }
}
