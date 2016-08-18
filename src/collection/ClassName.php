<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection;

use samsonframework\container\configurator\ClassConfiguratorInterface;
use samsonframework\container\configurator\ParameterConfiguratorInterface;
use samsonframework\container\configurator\PropertyConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\ParameterMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Class collection configurator class.
 *
 * @see    \samsonframework\container\configurator\ScopeConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 *
 */
class ClassName implements ClassConfiguratorInterface, PropertyConfiguratorInterface, ParameterConfiguratorInterface, CollectionAttributeConfiguratorInterface
{
    /**
     * @var string Configurator key
     */
    const CONFIGURATOR_KEY = 'class';

    /**
     * @var string Dependency class name
     */
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

    /* {@inheritDoc} */
    public function toParameterMetadata(ParameterMetadata $parameterMetadata)
    {
        $parameterMetadata->dependency = $this->className;
    }
}
