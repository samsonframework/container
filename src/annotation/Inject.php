<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */

namespace samsonframework\di\annotation;

/**
 * Class Scope.
 *
 * @Annotation
 */
class Inject
{
    public $list;

    public function __construct($list)
    {
        $this->list = $list;
    }
}
