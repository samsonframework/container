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
class PropertyDefinition extends AbstractDefinition
{
    /** @var string Property name */
    protected $propertyName;
    /** @var ReferenceInterface */
    protected $value;

    /**
     * PropertyDefinition constructor.
     *
     * @param AbstractDefinition $parentDefinition
     * @param string $propertyName
     */
    public function __construct(AbstractDefinition $parentDefinition, string $propertyName)
    {
        $this->parentDefinition = $parentDefinition;
        $this->propertyName = $propertyName;
    }

    /**
     * Define argument
     *
     * @param ReferenceInterface $value
     * @return PropertyDefinition
     */
    public function defineValue(ReferenceInterface $value) : PropertyDefinition
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get property metadata
     *
     * @param ClassMetadata $classMetadata
     * @return PropertyMetadata
     * @throws ReferenceNotImplementsException
     */
    public function toPropertyMetadata(ClassMetadata $classMetadata) : PropertyMetadata
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
     * @return ReferenceInterface
     */
    public function getValue(): ReferenceInterface
    {
        return $this->value;
    }
}
