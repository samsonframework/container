<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\analyzer;

use Doctrine\Common\Annotations\AnnotationReader;
use samsonframework\container\definition\analyzer\annotation\annotation\InjectClass;
use samsonframework\container\definition\analyzer\annotation\AnnotationMethodAnalyzer;
use samsonframework\container\definition\analyzer\annotation\AnnotationPropertyAnalyzer;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\exception\ParameterNotFoundException;
use samsonframework\container\definition\analyzer\reflection\ReflectionClassAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionMethodAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionParameterAnalyzer;
use samsonframework\container\definition\analyzer\reflection\ReflectionPropertyAnalyzer;
use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\tests\classes\annotation\PropClass;
use samsonframework\container\tests\classes\annotation\WrongPropClass;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\TestCaseDefinition;


class DefinitionAnnotationAnalyzerTest extends TestCaseDefinition
{
    public function callAnalyze(DefinitionBuilder $definitionBuilder)
    {
        $method = (new \ReflectionClass(DefinitionAnalyzer::class))->getMethod('analyze');
        $method->setAccessible(true);
        $reader = new AnnotationReader();
        $method->invoke(new DefinitionAnalyzer(
            [new ReflectionClassAnalyzer()],
            [new AnnotationMethodAnalyzer($reader), new ReflectionMethodAnalyzer()],
            [new AnnotationPropertyAnalyzer($reader), new ReflectionPropertyAnalyzer()],
            [new ReflectionParameterAnalyzer()]
        ), $definitionBuilder);
        $method->setAccessible(false);
    }

    public function testPropertyAnnotations()
    {
        new InjectClass('');

        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder->addDefinition(PropClass::class)->end();

        $this->callAnalyze($definitionBuilder);

//        $classDefinition = $this->getClassDefinition($definitionBuilder, Car::class);
//        $methodDefinition = $this->getMethodDefinition($definitionBuilder, Car::class, '__construct');
//        $parameterDefinition = $this->getParameterDefinition($definitionBuilder, Car::class, '__construct', 'driver');
        $propertyDefinition = $this->getPropertyDefinition($definitionBuilder, PropClass::class, 'car');

        static::assertEquals(Car::class, $propertyDefinition->getDependency()->getClassName());
        static::assertEquals(256, $propertyDefinition->getModifiers());
    }

    public function testMethodAnnotations()
    {
        new InjectClass('');

        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder->addDefinition(PropClass::class)->end();

        $this->callAnalyze($definitionBuilder);

        $parameterDefinition = $this->getParameterDefinition($definitionBuilder, PropClass::class, '__construct', 'car');
        static::assertEquals(Car::class, $parameterDefinition->getDependency()->getClassName());
    }

    public function testMethodAnnotationsWrongConstruct()
    {
        $this->expectException(ParameterNotFoundException::class);

        new InjectClass('');

        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder->addDefinition(WrongPropClass::class)->end();
        $this->callAnalyze($definitionBuilder);
    }
}
