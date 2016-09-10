<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\exception\ParameterDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\ParameterNotFoundException;

/**
 * Class MethodDefinition
 *
 * @package samsonframework\container\definition
 */
class MethodDefinition extends AbstractDefinition implements MethodBuilderInterface
{
    /** @var  string Method name */
    protected $methodName;
    /** @var ParameterDefinition[] Collection of parameter collection */
    protected $parametersCollection = [];
    /** @var int Method modifiers */
    protected $modifiers = 0;
    /** @var bool Flag that method is public */
    protected $isPublic = false;

    /**
     * Define arguments
     *
     * @param string $parameterName
     * @return ParameterBuilderInterface
     * @throws ParameterDefinitionAlreadyExistsException
     */
    public function defineParameter($parameterName): ParameterBuilderInterface
    {
        if (array_key_exists($parameterName, $this->parametersCollection)) {
            throw new ParameterDefinitionAlreadyExistsException();
        }

        $parameter = new ParameterDefinition($this);
        $parameter->setParameterName($parameterName);

        $this->parametersCollection[$parameterName] = $parameter;

        return $parameter;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return boolean
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param boolean $isPublic
     * @return MethodDefinition
     */
    public function setIsPublic(bool $isPublic): MethodDefinition
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @param string $methodName
     * @return MethodDefinition
     */
    public function setMethodName(string $methodName): MethodDefinition
    {
        $this->methodName = $methodName;

        return $this;
    }

    /**
     * @return int
     */
    public function getModifiers(): int
    {
        return $this->modifiers;
    }

    /**
     * @param int $modifiers
     * @return MethodDefinition
     */
    public function setModifiers(int $modifiers): MethodDefinition
    {
        $this->modifiers = $modifiers;

        return $this;
    }

    /**
     * @return ParameterDefinition[]
     */
    public function getParametersCollection(): array
    {
        return $this->parametersCollection;
    }
}
