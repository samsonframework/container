<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Class PropertyDefinition
 *
 * @package samsonframework\container\definition
 */
class PropertyDefinition extends AbstractPropertyDefinition implements PropertyBuilderInterface
{
    /** @var string Property name */
    protected $propertyName;
    /** @var bool Flag that property is public */
    public $isPublic = false;
    /** @var int Property modifiers */
    public $modifiers = 0;

    /**
     * Define dependency
     *
     * @param ReferenceInterface $dependency
     * @return PropertyBuilderInterface
     */
    public function defineDependency(ReferenceInterface $dependency): PropertyBuilderInterface
    {
        $this->dependency = $dependency;

        return $this;
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
     * @return PropertyDefinition
     */
    public function setPropertyName(string $propertyName): PropertyDefinition
    {
        $this->propertyName = $propertyName;

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
     * @return PropertyDefinition
     */
    public function setModifiers(int $modifiers): PropertyDefinition
    {
        $this->modifiers = $modifiers;

        return $this;
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
     * @return PropertyDefinition
     */
    public function setIsPublic(bool $isPublic): PropertyDefinition
    {
        $this->isPublic = $isPublic;

        return $this;
    }
}
