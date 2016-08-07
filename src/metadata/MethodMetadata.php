<?php
/**
 * Created by PhpStorm.
 * User: Vitaly Iegorov
 * Date: 08.08.2016
 * Time: 14:22.
 */
namespace samsonframework\container\metadata;

/**
 * Class MethodMetadata
 * @package samsonframework\container\metadata
 */
class MethodMetadata extends AbstractMetadata
{
    /** @var int Method modifiers */
    public $modifiers;

    /** @var ParameterMetadata[] */
    public $parametersMetadata = [];
}
