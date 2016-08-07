<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\ClassMetadata;

interface ClassInterface
{
    /**
     * Convert to class metadata.
     *
     * @param ClassMetadata $metadata Input metadata
     *
     * @return ClassMetadata Annotation conversion to metadata
     */
    public function toMetadata(ClassMetadata $metadata);
}
