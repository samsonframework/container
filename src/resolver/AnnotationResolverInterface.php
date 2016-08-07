<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:38
 */
namespace samsonframework\container\resolver;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Class annotation resolving interface.
 */
interface AnnotationResolverInterface
{
    /**
     * Resolve class annotations.
     *
     * @param \ReflectionClass $classData
     * @param ClassMetadata    $classMetadata
     *
     * @return mixed
     */
    public function resolve(\ReflectionClass $classData, ClassMetadata $classMetadata);
}
