<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\reflection;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\MethodAnalyzerInterface;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\MethodDefinition;

/**
 * Class ReflectionMethodAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ReflectionMethodAnalyzer implements MethodAnalyzerInterface
{
    /** {@inheritdoc} */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        \ReflectionMethod $reflectionMethod,
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition = null
    ) {
        if ($methodDefinition) {
            // Set method metadata
            $methodDefinition->setModifiers($reflectionMethod->getModifiers());
            $methodDefinition->setIsPublic($reflectionMethod->isPublic());
        }
    }
}
