<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\exception\ReferenceNotImplementsException;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\ParameterMetadata;

/**
 * Class ParameterDefinition
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ParameterDefinition extends AbstractPropertyDefinition implements ParameterBuilderInterface
{
    /** @var string Property name */
    protected $parameterName;

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
     * Get property metadata
     *
     * @param ClassMetadata $classMetadata
     * @param MethodMetadata $methodMetadata
     * @return ParameterMetadata
     * @throws ReferenceNotImplementsException
     */
    public function toPropertyMetadata(ClassMetadata $classMetadata, MethodMetadata $methodMetadata): ParameterMetadata
    {
        $propertyMetadata = new ParameterMetadata($classMetadata, $methodMetadata);
        $propertyMetadata->name = $this->getParameterName();
        $propertyMetadata->dependency = $this->resolveReferenceValue($this->getDependency());

        return $propertyMetadata;
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
}
