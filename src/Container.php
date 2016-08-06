<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */

namespace samsonframework\di;

use samsonframework\container\scope\ScopeManager;

/**
 * Class Container.
 */
abstract class Container
{
    /** Controller classes scope name */
    const SCOPE_CONTROLLER = 'controllers';
    /** Service classes scope name */
    const SCOPE_SERVICES = 'services';

    /**
     * @var ScopeManager
     */
    protected $scopeManager;

    /**
     * Container constructor.
     *
     * @param ScopeManager $scopeManager
     */
    public function __construct(ScopeManager $scopeManager)
    {
        $this->scopeManager = $scopeManager;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    abstract public function get($name);

    /**
     * Get scope manager.
     *
     * @return ScopeManager
     */
    public function getScopeManager()
    {
        return $this->scopeManager;
    }
}
