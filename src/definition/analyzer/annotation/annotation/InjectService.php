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
use samsonframework\container\definition\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\MethodDefinitionNotFoundException;
use samsonframework\container\definition\exception\ParameterDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\PropertyDefinitionNotFoundException;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\PropertyDefinition;
use samsonframework\container\definition\reference\ServiceReference;

/**
 * Injection annotation service.
 *
 * @Annotation
 */
class InjectService implements ResolvePropertyInterface, ResolveMethodInterface
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

    /**
     * {@inheritdoc}
     * @throws PropertyDefinitionNotFoundException
     */
    public function resolveProperty(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionProperty $reflectionProperty
    ) {
        $propertyName = $reflectionProperty->getName();
        if ($classDefinition->hasProperty($propertyName)) {
            $classDefinition->getProperty($propertyName)
                ->defineDependency(new ServiceReference($this->value['value']));
        }
    }

    /**
     * {@inheritdoc}
     * @throws WrongAnnotationConstructorException
     * @throws MethodDefinitionNotFoundException
     * @throws ParameterDefinitionAlreadyExistsException
     * @throws MethodDefinitionAlreadyExistsException
     */
    public function resolveMethod(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionMethod $reflectionMethod
    ) {
        // Get parameter key
        $key = array_keys($this->value)[0];
        $classDefinition->setupMethod($reflectionMethod->getName())
            ->defineParameter($key)
            ->defineDependency(new ServiceReference($this->value[$key]))->end();
    }
}
