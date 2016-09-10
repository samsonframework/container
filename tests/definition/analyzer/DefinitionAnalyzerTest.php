<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\analyzer;

use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\definition\builder\DefinitionCompiler;
use samsonframework\container\definition\builder\DefinitionGenerator;
use samsonframework\container\definition\exception\ParameterNotFoundException;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\classes\WheelController;
use samsonframework\container\tests\TestCaseDefinition;
use samsonframework\generator\ClassGenerator;


class DefinitionAnalyzerTest extends TestCaseDefinition
{
    public function callAnalyze(DefinitionBuilder $definitionBuilder)
    {
        $method = (new \ReflectionClass(DefinitionCompiler::class))->getMethod('analyze');
        $method->setAccessible(true);
        $method->invoke(new DefinitionCompiler(new DefinitionGenerator(new ClassGenerator())), $definitionBuilder);
        $method->setAccessible(false);
    }

    public function testAddDefinition()
    {

    }
}
