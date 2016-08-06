<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.08.16
 * Time: 0:06.
 */

namespace samsonframework\container\scope;

class ScopeManager
{
    /**
     * @var array
     */
    protected $list;

    /**
     * Add scope to list.
     *
     * @param Scope $scope
     */
    public function add(Scope $scope)
    {
        $this->list[$scope->getName()] = $scope;
    }

    /**
     * Get scope.
     *
     * @param string $name
     *
     * @return Scope
     *
     * @throws \Exception
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->list)) {
            throw new \Exception(sprintf('Scope %s not found', $name));
        }

        return $this->list[$name];
    }

    /**
     * Scope exists.
     *
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->list);
    }

    /**
     * Get list of scopes.
     *
     * @return Scope[]
     */
    public function getList()
    {
        return $this->list;
    }
}
