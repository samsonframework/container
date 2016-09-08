<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\analyzer\ParameterAnalyzerInterface;
use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Class ParameterDefinition
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ParameterDefinition extends AbstractPropertyDefinition implements ParameterBuilderInterface, ParameterAnalyzerInterface
{
    /** @var string Property name */
    protected $parameterName;
    /** @var bool If optional parameter */
    protected $isOptional;
    /** @var \ReflectionType Property typeHint from typeHint hint */
    protected $typeHint;
    /** @var mixed Property value */
    public $value;

    /**
     * Define argument
     *
     * @param ReferenceInterface $dependency
     * @return ParameterBuilderInterface
     */
    public function defineDependency(ReferenceInterface $dependency): ParameterBuilderInterface
    {
        $this->dependency = $dependency;

        return $this;
    }

    /** {@inheritdoc} */
    public function analyze(DefinitionAnalyzer $analyzer, \ReflectionParameter $reflectionParameter)
    {
        // Set parameter metadata
        if ($reflectionParameter->isDefaultValueAvailable()) {
            $this->setValue($reflectionParameter->getDefaultValue());
        }
        $this->setTypeHint($reflectionParameter->getType());
        $this->setIsOptional($reflectionParameter->isOptional());
    }

    /**
     * @return string
     */
    public function getParameterName(): string
    {
        return $this->parameterName;
    }

    /**
     * @param string $parameterName
     * @return ParameterDefinition
     */
    public function setParameterName(string $parameterName): ParameterDefinition
    {
        $this->parameterName = $parameterName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isOptional()
    {
        return $this->isOptional;
    }

    /**
     * @param mixed $isOptional
     * @return ParameterDefinition
     */
    public function setIsOptional($isOptional)
    {
        $this->isOptional = $isOptional;

        return $this;
    }

    /**
     * @return \ReflectionType
     */
    public function getTypeHint(): \ReflectionType
    {
        return $this->typeHint;
    }

    /**
     * @param \ReflectionType $typeHint
     * @return AbstractPropertyDefinition
     */
    public function setTypeHint(\ReflectionType $typeHint): AbstractPropertyDefinition
    {
        $this->typeHint = $typeHint;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return AbstractPropertyDefinition
     */
    public function setValue($value): AbstractPropertyDefinition
    {
        $this->value = $value;

        return $this;
    }
}
