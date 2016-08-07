<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\MethodMetadata;

interface MethodInterface
{
    /**
     * Convert to class method metadata.
     *
     * @param MethodMetadata $metadata Input metadata
     *
     * @return MethodMetadata Annotation conversion to metadata
     */
    public function toMetadata(MethodMetadata $metadata);
}
