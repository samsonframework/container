<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Service annotation class.
 *
 * This annotation adds class to Service container scope.
 * @see samsonframework\container\Container::SCOPE_SERVICE
 *
 * @Annotation
 */
class Service implements MetadataInterface
{
    /** @var string Service unique name */
    public $name;

    /**
     * Service constructor.
     *
     * @param string $name Service unique name
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($name)
    {
        if (is_array($name) && array_key_exists('value', $name)) {
            $this->name = $name['value'];
        } else {
            throw new \InvalidArgumentException('Service annotation should have name');
        }
    }

    /** {@inheritdoc} */
    public function toMetadata(ClassMetadata &$metadata)
    {
        $metadata->name = $this->name;
    }
}
