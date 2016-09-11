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
class ParameterBuilder extends AbstractDefinition implements ParameterBuilderInterface
{
    /** @var array */
    protected $parameterCollection = [];

    /**
     * Define parameter
     *
     * @param string $name
     * @param ReferenceInterface $reference
     * @return ParameterBuilderInterface
     * @throws ParameterAlreadyExistsException
     */
    public function defineParameter(string $name, ReferenceInterface $reference): ParameterBuilderInterface
    {
        return $this->add($name, $reference);
    }

    /**
     * Change parameter
     *
     * @param string $name
     * @param ReferenceInterface $reference
     * @return ParameterBuilder
     * @throws ParameterNotFoundException
     */
    public function changeParameter(string $name, ReferenceInterface $reference): ParameterBuilder
    {
        if (!$this->has($name)) {
            throw new ParameterNotFoundException(sprintf('Parameter with name "%s" not found', $name));
        }
        $this->parameterCollection[$name] = $reference;

        return $this;
    }

    /**
     * Add parameter to collection
     *
     * @param string $name
     * @param ReferenceInterface $reference
     * @return ParameterBuilder
     * @throws ParameterAlreadyExistsException
     */
    public function add(string $name, ReferenceInterface $reference): ParameterBuilder
    {
        if ($this->has($name)) {
            throw new ParameterAlreadyExistsException(sprintf('Parameter with name "%s" already defined', $name));
        }
        $this->parameterCollection[$name] = $reference;

        return $this;
    }

    /**
     * Has parameter in collection
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->parameterCollection);
    }

    /**
     * Remove parameter
     *
     * @param string $name
     * @throws ParameterNotFoundException
     */
    public function remove(string $name)
    {
        if (!$this->has($name)) {
            throw new ParameterNotFoundException(sprintf('Parameter with name "%s" not found', $name));
        }
        unset($this->parameterCollection[$name]);
    }

    /**
     * Get parameter
     *
     * @param string $name
     * @return ReferenceInterface
     * @throws ParameterNotFoundException
     */
    public function get(string $name): ReferenceInterface
    {
        if (!$this->has($name)) {
            throw new ParameterNotFoundException(sprintf('Parameter with name "%s" not found', $name));
        }
        return $this->parameterCollection[$name];
    }

    /**
     * Get parameter collection
     *
     * @return ReferenceInterface[]
     */
    public function getParameterCollection(): array
    {
        return $this->parameterCollection;
    }
}
