<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer\annotation\annotation;

use samsonframework\container\definition\analyzer\annotation\ResolveClassInterface;
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
class Service implements ResolveClassInterface
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
    public function resolveClass(
        DefinitionAnalyzer $analyzer,
        ClassDefinition $classDefinition,
        \ReflectionClass $reflectionClass
    ) {
        $classDefinition->setServiceName($this->value['value']);
        $classDefinition->setIsSingleton(true);
    }
}
