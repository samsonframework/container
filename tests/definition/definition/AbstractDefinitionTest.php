<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\exception\ParentDefinitionNotFoundException;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\tests\TestCaseDefinition;


class AbstractDefinitionTest extends TestCaseDefinition
{
    public function testParentDefinition()
    {
        $classDefinition = new ClassDefinition();
        $propertyDefinition = new PropertyDefinition($classDefinition);
        static::assertInstanceOf(ClassDefinition::class, $propertyDefinition->getParentDefinition());
    }

    public function testEnd()
    {
        $classDefinition = new ClassDefinition();
        $propertyDefinition = new PropertyDefinition($classDefinition);
        static::assertInstanceOf(ClassDefinition::class, $propertyDefinition->end());
    }

    public function testException()
    {
        $this->expectException(ParentDefinitionNotFoundException::class);

        $propertyDefinition = new PropertyDefinition();
        $propertyDefinition->end();
    }
}
