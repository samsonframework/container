<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\exception\ReferenceNotImplementsException;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\ParameterMetadata;

/**
 * Class MethodDefinition
 *
 * @package samsonframework\container\definition
 */
class MethodDefinition extends AbstractDefinition
{
    /** @var  string Method name */
    protected $methodName;
    /** @var ReferenceInterface[] */
    protected $arguments;

    /**
     * MethodDefinition constructor.
     *
     * @param AbstractDefinition $parentDefinition
     * @param string $methodName
     */
    public function __construct(AbstractDefinition $parentDefinition, string $methodName)
    {
        $this->parentDefinition = $parentDefinition;
        $this->methodName = $methodName;
    }

    /**
     * Resolve method metadata
     *
     * @param ClassMetadata $classMetadata
     * @return MethodMetadata
     * @throws ReferenceNotImplementsException
     */
    public function toMethodMetadata(ClassMetadata $classMetadata) : MethodMetadata
    {
        $methodMetadata = new MethodMetadata($classMetadata);
        $methodMetadata->name = $this->getMethodName();

        // Resolve arguments
        foreach ($this->arguments as $parameterName => $argument) {
            $methodMetadata->dependencies[$parameterName] = $this->resolveReference($argument);
            $methodMetadata->parametersMetadata = new ParameterMetadata($classMetadata, $methodMetadata);
            $methodMetadata->parametersMetadata->name = $parameterName;
        }
        return $methodMetadata;
    }

    /**
     * Define arguments
     *
     * @param array $arguments
     * @return MethodDefinition
     */
    public function defineArguments(array $arguments) : MethodDefinition
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * Get arguments
     *
     * @return reference\ReferenceInterface[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
