<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

/**
 * Class ParameterReference
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ParameterReference implements ReferenceInterface
{
    /** @var string Parameter name */
    protected $parameterName;

    /**
     * ParameterReference constructor.
     *
     * @param string $parameterName
     */
    public function __construct(string $parameterName)
    {
        $this->parameterName = $parameterName;
    }

    /**
     * @param string $parameterName
     * @return ParameterReference
     */
    public function setParameterName(string $parameterName): ParameterReference
    {
        $this->parameterName = $parameterName;

        return $this;
    }

    /**
     * @return string
     */
    public function getParameterName(): string
    {
        return $this->parameterName;
    }
}
