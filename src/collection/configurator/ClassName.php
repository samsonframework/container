<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection\configurator;

use samsonframework\container\collection\CollectionAttributeConfiguratorInterface;
use samsonframework\container\collection\CollectionConfiguratorTrait;
use samsonframework\container\configurator\ClassConfiguratorInterface;
use samsonframework\container\configurator\PropertyConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * ClassName collection attribute configurator class.
 *
 * This attribute can be used for property and for class configuration.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ClassName implements ClassConfiguratorInterface, PropertyConfiguratorInterface, CollectionAttributeConfiguratorInterface
{
    use CollectionConfiguratorTrait;

    /** @var string Dependency class name */
    protected $className;

    /**
     * Class collection configurator constructor.
     *
     * @param string $className Class name
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /* {@inheritDoc} */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->className = $this->className;
    }

    /* {@inheritDoc} */
    public function toPropertyMetadata(PropertyMetadata $propertyMetadata)
    {
        $propertyMetadata->dependency = $this->className;
    }
}
