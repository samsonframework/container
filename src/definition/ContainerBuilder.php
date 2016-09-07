<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\exception\ClassDefinitionAlreadyExistsException;

/**
 * Class ContainerBuilder
 *
 * @package samsonframework\container\definition
 */
class ContainerBuilder
{
    /** @var  ClassDefinition[] Definition collection */
    protected $definitionCollection = [];

    /**
     * Add new class definition
     *
     * @param $className
     * @param string $serviceName
     * @return ClassDefinition
     * @throws ClassDefinitionAlreadyExistsException
     */
    public function addDefinition($className, string $serviceName = null) : ClassDefinition
    {
        // Create new definition
        $definition = new ClassDefinition($className, $serviceName);

        // Check if class already exists
        if (array_key_exists($className, $this->definitionCollection)) {
            throw new ClassDefinitionAlreadyExistsException();
        }
        // Register definition
        $this->definitionCollection[$className] = $definition;

        return $definition;
    }

    /**
     * Convert to metadata collection
     *
     * @return array
     */
    public function toMetadataCollection() : array
    {
        $metadataCollection = [];
        foreach ($this->definitionCollection as $classDefinition) {
            $metadataCollection[$classDefinition->getClassName()] = $classDefinition->toMetadata();
        }

        return $metadataCollection;
    }
}
