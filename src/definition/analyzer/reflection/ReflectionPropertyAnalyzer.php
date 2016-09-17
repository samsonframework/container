<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\reflection;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\PropertyAnalyzerInterface;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\exception\PropertyDefinitionNotFoundException;

/**
 * Class ReflectionPropertyAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ReflectionPropertyAnalyzer implements PropertyAnalyzerInterface
{
    /**
     * {@inheritdoc}
     * @throws PropertyDefinitionNotFoundException
     */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionProperty $reflectionProperty
    ) {
        $propertyName = $reflectionProperty->getName();
        // Set property metadata
        if ($classDefinition->hasProperty($propertyName)) {
            $classDefinition->getProperty($propertyName)
                ->setIsPublic($reflectionProperty->isPublic())
                ->setModifiers($reflectionProperty->getModifiers());
        }
    }
}
