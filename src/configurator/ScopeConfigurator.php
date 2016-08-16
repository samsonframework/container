<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\configurator;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Scope class configurator.
 *
 * This configurator adds class to container scopes.
 * @see    samsonframework\container\Container::$scopes
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class ScopeConfigurator implements ClassConfiguratorInterface
{
    /** @var string Class scope name */
    protected $scopeName;

    /**
     * ScopeConfigurator constructor.
     *
     * @param string $scopeName
     */
    public function __construct(string $scopeName)
    {
        $this->scopeName = $scopeName;
    }

    /** {@inheritdoc} */
    public function toClassMetadata(ClassMetadata $classMetadata)
    {
        // Add scope name to class scopes collection
        $classMetadata->scopes[] = $this->scopeName;
    }
}
