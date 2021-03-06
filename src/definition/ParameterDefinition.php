<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Class ParameterDefinition
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ParameterDefinition extends AbstractPropertyDefinition implements ParameterBuilderInterface
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
    public function setIsOptional($isOptional): ParameterDefinition
    {
        $this->isOptional = $isOptional;

        return $this;
    }

    /**
     * @return \ReflectionType|null
     */
    public function getTypeHint()
    {
        return $this->typeHint;
    }

    /**
     * @param \ReflectionType $typeHint
     * @return ParameterDefinition
     */
    public function setTypeHint(\ReflectionType $typeHint): ParameterDefinition
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
     * @return ParameterDefinition
     */
    public function setValue($value): ParameterDefinition
    {
        $this->value = $value;

        return $this;
    }
}
