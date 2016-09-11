<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\UndefinedReference;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\TestCaseDefinition;


class AbstractPropertyDefinitionTest extends TestCaseDefinition
{
    public function testInitDependencyReferenceIsUndefined()
    {
        $definition = new PropertyDefinition();
        static::assertInstanceOf(UndefinedReference::class, $definition->getDependency());
    }

    public function testSetDependency()
    {
        $definition = (new PropertyDefinition())->setDependency(new ClassReference(Car::class));
        static::assertInstanceOf(ClassReference::class, $definition->getDependency());
    }
}
