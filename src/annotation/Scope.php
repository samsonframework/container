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
class Scope
{
    public $scopes = [];

    public function __construct($scope)
    {
        if ($scope) {
            $value = $scope['value'];
            if (!is_array($value) && !is_string($value)) {
                throw new \Exception('Wrong type of alias');
            }
            $this->scopes = is_string($value) ? [$value] : $value;
        }
    }
}
