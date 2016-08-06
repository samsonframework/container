<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */

namespace samsonframework\di\annotation;

/**
 * Class Service.
 *
 * @Annotation
 */
class Service implements Bean
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}
