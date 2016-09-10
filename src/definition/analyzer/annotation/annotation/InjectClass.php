<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\annotation\annotation;

use samsonframework\container\definition\analyzer\annotation\ResolveMethodInterface;
use samsonframework\container\definition\analyzer\annotation\ResolvePropertyInterface;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\exception\WrongAnnotationConstructorException;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\reference\ClassReference;

/**
 * Injection annotation class.
 *
 * @Annotation
 */
class InjectClass implements ResolvePropertyInterface, ResolveMethodInterface
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

    /**
     * {@inheritdoc}
     * @throws WrongAnnotationConstructorException
     */
    public function resolveMethod(
        DefinitionAnalyzer $analyzer,
        \ReflectionMethod $reflectionMethod,
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition
    ) {
        // Get parameter key
        $key = array_keys($this->value)[0];
        if (!is_array($this->value) || count($this->value) === 0) {
            throw new WrongAnnotationConstructorException();
        }
        // Add dependency
        $methodDefinition->defineParameter($key)->defineDependency($this->value[$key])->end();
    }
}
