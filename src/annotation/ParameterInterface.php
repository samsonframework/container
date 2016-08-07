<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\ParameterMetadata;

/**
 * Method parameter annotation interface.
 *
 * @package samsonframework\container\annotation
 */
interface ParameterInterface extends AnnotationInterface
{
    /**
     * Convert to class property metadata.
     *
     * @param ParameterMetadata $propertyMetadata Input metadata
     *
     * @return ParameterMetadata Annotation conversion to metadata
     */
    public function toParameterMetadata(ParameterMetadata $parameterMetadata);
}
