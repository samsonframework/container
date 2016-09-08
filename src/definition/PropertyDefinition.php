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

    /**
     * Define dependency
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
}
