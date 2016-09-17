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
use samsonframework\container\definition\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\MethodDefinitionNotFoundException;

/**
 * Class ReflectionMethodAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ReflectionMethodAnalyzer implements MethodAnalyzerInterface
{
    /**
     * {@inheritdoc}
     * @throws MethodDefinitionAlreadyExistsException
     * @throws MethodDefinitionNotFoundException
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionMethod $reflectionMethod
    ) {
        $methodName = $reflectionMethod->getName();
        // Constructor definition is required
        if ($methodName === '__construct' && !$classDefinition->hasMethod('__construct')) {
            $classDefinition->defineConstructor()->end();
        }
        // Set method metadata
        if ($classDefinition->hasMethod($methodName)) {
            $classDefinition->getMethod($methodName)
                ->setModifiers($reflectionMethod->getModifiers())
                ->setIsPublic($reflectionMethod->isPublic());
        }
    }
}
