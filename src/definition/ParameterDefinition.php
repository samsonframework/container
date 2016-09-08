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
}
