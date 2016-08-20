<?php declare(strict_types = 1);
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
class ParameterMetadata extends PropertyMetadata
{
    /** @var MethodMetadata */
    public $methodMetadata;

    /**
     * ParameterMetadata constructor.
     *
     * @param MethodMetadata $methodMetadata
     */
    public function __construct(ClassMetadata $classMetadata, MethodMetadata $methodMetadata)
    {
        parent::__construct($classMetadata);

        $this->methodMetadata = $methodMetadata;
    }
}
