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
 * Class DefinitionBuilder
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class DefinitionBuilder extends AbstractDefinition
{
    /** @var  ClassDefinition[] Definition collection */
    protected $definitionCollection = [];

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
        if (array_key_exists($className, $this->definitionCollection)) {
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
     * Convert to metadata collection
     *
     * @return array
     */
    public function toMetadataCollection(): array
    {
        $metadataCollection = [];
        foreach ($this->definitionCollection as $classDefinition) {
            $metadataCollection[$classDefinition->getClassName()] = $classDefinition->toMetadata();
        }

        return $metadataCollection;
    }
}
