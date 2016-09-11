<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\builder;

use samsonframework\container\definition\AbstractDefinition;
use samsonframework\container\definition\ClassBuilderInterface;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\exception\ClassDefinitionAlreadyExistsException;
use samsonframework\container\definition\parameter\exception\ParameterAlreadyExistsException;
use samsonframework\container\definition\parameter\ParameterBuilder;
use samsonframework\container\definition\parameter\ParameterBuilderInterface;
use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Class DefinitionBuilder
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class DefinitionBuilder extends AbstractDefinition
{
    /** @var  ClassDefinition[] Definition collection */
    protected $definitionCollection = [];
    /** @var  ParameterBuilder */
    protected $parameterBuilder;

    /**
     * DefinitionBuilder constructor.
     *
     * @param ParameterBuilder $parameterBuilder
     * @param AbstractDefinition|null $parentDefinition
     */
    public function __construct(ParameterBuilder $parameterBuilder, AbstractDefinition $parentDefinition = null)
    {
        parent::__construct($parentDefinition);

        $this->parameterBuilder = $parameterBuilder;
        $this->parameterBuilder->setParentDefinition($this);
    }

    /**
     * Define parameters
     *
     * @param string $name
     * @param ReferenceInterface $reference
     * @return ParameterBuilderInterface
     * @throws ParameterAlreadyExistsException
     */
    public function defineParameter(string $name, ReferenceInterface $reference): ParameterBuilderInterface
    {
        return $this->parameterBuilder->defineParameter($name, $reference);
    }

    /**
     * Add new class definition
     *
     * @param $className
     * @param string $serviceName
     * @return ClassBuilderInterface
     * @throws ClassDefinitionAlreadyExistsException
     */
    public function addDefinition($className, string $serviceName = null): ClassBuilderInterface
    {
        // Check if class already exists
        if ($this->hasDefinition($className)) {
            throw new ClassDefinitionAlreadyExistsException();
        }

        // Create new definition
        $classDefinition = new ClassDefinition($this);
        $classDefinition->setClassName($className);
        if ($serviceName) {
            $classDefinition->setServiceName($serviceName);
        }

        // Register definition
        $this->definitionCollection[$className] = $classDefinition;

        return $classDefinition;
    }

    /**
     * When definition for class name is exists in collection
     *
     * @param $className
     * @return bool
     */
    public function hasDefinition($className): bool
    {
        return array_key_exists($className, $this->definitionCollection);
    }

    /**
     * @return ClassDefinition[]
     */
    public function getDefinitionCollection(): array
    {
        return $this->definitionCollection;
    }

    /**
     * @return ReferenceInterface[]
     */
    public function getParameterCollection(): array
    {
        return $this->parameterBuilder->getParameterCollection();
    }
}
