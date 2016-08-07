<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 14:58
 */
namespace samsonframework\container\metadata;

use samsonframework\container\annotation\AnnotationInterface;

abstract class AbstractMetadata
{
    /** @var string */
    public $name;

    /** @var AnnotationInterface[] */
    public $annotations;
}
