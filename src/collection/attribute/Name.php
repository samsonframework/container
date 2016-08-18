<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection\attribute;

use samsonframework\container\configurator\ClassConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Name attribute configurator class.
 *
 * @see    \samsonframework\container\configurator\ScopeConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Name implements ClassConfiguratorInterface, AttributeConfiguratorInterface
{
    /** @var string Configurator key */
    const KEY = 'name';

    /** @var string Dependency name */
    protected $name;

    /**
     * Dependency name collection configurator constructor.
     *
     * @param string $name Dependency name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->name = $this->name;
    }
}
