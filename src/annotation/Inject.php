<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\PropertyMetadata;

/**
 * Injection annotation class.
 *
 * @Annotation
 */
class Inject implements PropertyInterface
{
    /** @var string Injectable dependency */
    protected $dependency;

    /**
     * Inject constructor.
     *
     * @param array $valueOrValues
     */
    public function __construct(array $valueOrValues)
    {
        // Get first argument from annotation
        $this->dependency = $valueOrValues['value'] ?? null;

        // Convert empty dependency to null
        $this->dependency = $this->dependency !== '' ? $this->dependency : null;

        // Removed first namespace separator if present
        $this->dependency = is_string($this->dependency) ? ltrim($this->dependency, '\\') : $this->dependency;
    }

    /** {@inheritdoc} */
    public function toPropertyMetadata(PropertyMetadata $propertyMetadata)
    {
        // Get @Inject("value")
        $propertyMetadata->dependency = $this->dependency;

        $this->validate(
            $propertyMetadata->typeHint,
            $propertyMetadata->dependency,
            $propertyMetadata->classMetadata->nameSpace
        );

        // Store property visibility
        $propertyMetadata->isPublic = ($propertyMetadata->modifiers & \ReflectionProperty::IS_PUBLIC) === \ReflectionProperty::IS_PUBLIC;
    }

    /**
     * Validate dependency.
     *
     * @param string $type
     * @param string $dependency
     * @param string $namespace
     */
    protected function validate(&$type, &$dependency, $namespace)
    {
        $dependency = $this->buildFullClassName($dependency, $namespace);
        $type = $this->buildFullClassName($type, $namespace);

        // Check for inheritance violation
        if ($this->checkInheritanceViolation($type, $dependency)) {
            throw new \InvalidArgumentException(
                '@Inject dependency violates ' . $type . ' inheritance with ' . $dependency
            );
        }

        if ($this->checkInterfaceWithoutClassName($type, $dependency)) {
            throw new \InvalidArgumentException(
                'Cannot @Inject interface, inherited class name should be specified
                ');
        }

        // Empty @Inject with type hint - use type hine as dependency
        if ($dependency === null && $type !== null) {
            $dependency = $type;
        }
    }

    /**
     * Build full class name.
     *
     * @param string $className Full or short class name
     * @param string $namespace Name space
     *
     * @return string Full class name
     */
    protected function buildFullClassName($className, $namespace)
    {
        // Check if we need to append namespace to dependency
        if ($className !== null && strpos($className, '\\') === false) {
            return $namespace . '\\' . $className;
        }

        return $className;
    }

    /**
     * Check if @Inject violates inheritance.
     *
     * @param string $type       Property/Parameter type
     * @param string $dependency @Inject value
     *
     * @return bool True if @Inject violates inheritance
     */
    protected function checkInheritanceViolation($type, $dependency) : bool
    {
        // Check for inheritance violation
        if ($dependency !== null && $type !== null) {
            $inheritance = array_merge(
                [$dependency],
                class_parents($dependency),
                class_implements($dependency)
            );
            return !in_array($type, $inheritance, true);
        }

        return false;
    }

    /**
     * Check if @Inject has no class name and type hint is interface.
     *
     * @param string $type       Property/Parameter type
     * @param string $dependency @Inject value
     *
     * @return bool True if @Inject has no class name and type hint is interface.
     */
    protected function checkInterfaceWithoutClassName($type, $dependency) : bool
    {
        return $type !== null
        && $dependency === null
        && (new \ReflectionClass($type))->isInterface();
    }
}
