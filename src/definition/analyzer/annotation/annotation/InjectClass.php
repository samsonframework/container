<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\annotation\annotation;

use samsonframework\container\definition\analyzer\annotation\ResolvePropertyInterface;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\reference\ClassReference;

/**
 * Injection annotation class.
 *
 * @Annotation
 */
class InjectClass implements ResolvePropertyInterface
{
    /** @var mixed */
    protected $value;

    /**
     * Inject constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /** {@inheritdoc} */
    public function resolveProperty(
        DefinitionAnalyzer $analyzer,
        \ReflectionProperty $reflectionProperty,
        ClassDefinition $classDefinition,
        PropertyDefinition $propertyDefinition
    ) {
        $propertyDefinition->defineDependency(new ClassReference($this->value['value']));
    }
}
