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
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Class PropertyDefinition
 *
 * @package samsonframework\container\definition
 */
class PropertyDefinition extends AbstractPropertyDefinition implements PropertyBuilderInterface
{
    /** @var string Property name */
    protected $propertyName;

    /**
     * Define argument
     *
     * @param ReferenceInterface $dependency
     * @return PropertyDefinition
     */
    public function defineDependency(ReferenceInterface $dependency): PropertyDefinition
    {
        $this->dependency = $dependency;

        return $this;
    }

    /**
     * Get property metadata
     *
     * @param ClassMetadata $classMetadata
     * @return PropertyMetadata
     * @throws ReferenceNotImplementsException
     */
    public function toPropertyMetadata(ClassMetadata $classMetadata): PropertyMetadata
    {
        $propertyMetadata = new PropertyMetadata($classMetadata);
        $propertyMetadata->name = $this->getPropertyName();
        $propertyMetadata->dependency = $this->resolveReference($this->getValue());

        return $propertyMetadata;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     * @return AbstractPropertyDefinition
     */
    public function setPropertyName(string $propertyName): AbstractPropertyDefinition
    {
        $this->propertyName = $propertyName;

        return $this;
    }
}
