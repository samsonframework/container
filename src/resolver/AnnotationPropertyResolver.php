<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\resolver;


use Doctrine\Common\Annotations\AnnotationReader;

class AnnotationPropertyResolver
{
    /** Property typeHint hint pattern */
    const P_PROPERTY_TYPE_HINT = '/@var\s+(?<class>[^\s]+)/';

    /** @var AnnotationReader */
    protected $reader;


}
