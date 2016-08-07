<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Class annotation interface.
 *
 * @package samsonframework\container\annotation
 */
interface ClassInterface extends AnnotationInterface
{
    /**
     * Convert to class metadata.
     *
     * @param ClassMetadata $classMetadata Input metadata
     *
     * @return ClassMetadata Annotation conversion to metadata
     */
    public function toClassMetadata(ClassMetadata $classMetadata);
}
