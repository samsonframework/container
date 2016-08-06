<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.08.16
 * Time: 0:57.
 */

namespace samsonframework\di\annotation;

interface MethodAnnotation
{
    public function convertToMetadata();
    public function getMethodAlias();
}
