<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 11.09.2016
 * Time: 14:47
 */
namespace samsonframework\container\definition\parameter;

use samsonframework\container\definition\AbstractDefinition;
use samsonframework\container\definition\parameter\exception\ParameterAlreadyExistsException;
use samsonframework\container\definition\parameter\exception\ParameterNotFoundException;
use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Class ParameterBuilder
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ParameterBuilder extends AbstractDefinition
{
    /** @var array */
    protected $parameterCollection = [];

    public function defineParameter(string $name, ReferenceInterface $reference)
    {
        return $this->add($name, $reference);
    }

    public function add(string $name, ReferenceInterface $reference): ParameterBuilder
    {
        if ($this->has($name)) {
            throw new ParameterAlreadyExistsException(sprintf('Parameter with name "%s" already defined', $name));
        }
        $this->parameterCollection[$name] = $reference;

        return $this;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->parameterCollection);
    }

    public function remove(string $name)
    {
        if (!$this->has($name)) {
            throw new ParameterNotFoundException(sprintf('Parameter with name "%s" not found', $name));
        }
        unset($this->parameterCollection[$name]);
    }

    public function get(string $name): ReferenceInterface
    {
        if (!$this->has($name)) {
            throw new ParameterNotFoundException(sprintf('Parameter with name "%s" not found', $name));
        }
        return $this->parameterCollection[$name];
    }
}
