<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition;

use samsonframework\container\definition\DefinitionBuilder;
use samsonframework\container\definition\DefinitionFiller;
use samsonframework\container\definition\ParameterDefinition;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\exception\ParentDefinitionNotFoundException;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\CarController;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\Leg;
use samsonframework\container\tests\TestCaseDefinition;


class DefinitionFillerTest extends TestCaseDefinition
{
    public function testAddDefinition()
    {
        $class = Car::class;
        $builder = (new DefinitionBuilder())
            ->addDefinition($class, 'car')->end();

        $filler = new DefinitionFiller();
        $filler->compile($builder);

        static::assertEquals($class, $this->getClassDefinition($builder, $class)->getClassName());
        static::assertEquals('car', $this->getClassDefinition($builder, $class)->getServiceName());
    }
}
