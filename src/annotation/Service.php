<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */

namespace samsonframework\container\annotation;

/**
 * Service annotation class.
 *
 * This annotation adds class to Service container scope.
 * @see samsonframework\container\Container::SCOPE_SERVICE
 *
 * @Annotation
 */
class Service implements ParentInterface
{
    /** @var string Service unique name */
    public $name;

    /**
     * Service constructor.
     *
     * @param string $name Service unique name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}
