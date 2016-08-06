<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */

namespace samsonframework\di;

use samsonframework\di\scope\ScopeManager;

/**
 * Class Container.
 */
abstract class Container
{
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
