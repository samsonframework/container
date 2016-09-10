<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\reflection;

use samsonframework\container\definition\analyzer\ClassAnalyzerInterface;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\ClassDefinition;

/**
 * Class ReflectionClassAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ReflectionClassAnalyzer implements ClassAnalyzerInterface
{
    /** {@inheritdoc} */
    public function analyze(DefinitionAnalyzer $analyzer, ClassDefinition $classDefinition, \ReflectionClass $reflectionClass)
    {
        // Get name space from class name
        $classDefinition->setNameSpace($reflectionClass->getNamespaceName());
    }
}
