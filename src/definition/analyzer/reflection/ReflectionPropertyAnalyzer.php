<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\reflection;

use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\PropertyAnalyzerInterface;
use samsonframework\container\definition\PropertyDefinition;

/**
 * Class ReflectionPropertyAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ReflectionPropertyAnalyzer implements PropertyAnalyzerInterface
{
    /** {@inheritdoc} */
    public function analyze(
        DefinitionAnalyzer $analyzer,
        PropertyDefinition $propertyDefinition,
        \ReflectionProperty $reflectionProperty
    ) {
        // Set property metadata
        $propertyDefinition->setIsPublic($reflectionProperty->isPublic());
        $propertyDefinition->setModifiers($reflectionProperty->getModifiers());
    }
}
