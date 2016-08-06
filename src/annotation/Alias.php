<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 1:59.
 */

namespace samsonframework\di\annotation;

/**
 * Class AutoWire.
 *
 * @Annotation
 */
class Alias
{
    public $aliases = [];

    public function __construct($value)
    {
        if (!is_array($value) && is_string($value)) {
            throw new \Exception('Wrong type of alias');
        }
        $this->aliases = is_string($value) ? [$value] : $value;
    }
}
