<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 22:22.
 */
namespace samsonframework\container\metadata;

/**
 * Method parameter metadata.
 */
class ParameterMetadata extends AbstractMetadata
{
    /** @var MethodMetadata */
    public $methodMetadata;

    /** @var int Property modifiers */
    public $modifiers;

    /** @var string Property type */
    public $type;

    /**
     * ParameterMetadata constructor.
     *
     * @param MethodMetadata $methodMetadata
     */
    public function __construct(MethodMetadata $methodMetadata)
    {
        $this->methodMetadata = $methodMetadata;
    }
}
