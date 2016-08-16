<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\annotation;

use samsonframework\container\configurator\MethodConfiguratorInterface;
use samsonframework\container\metadata\MethodMetadata;

/**
 * Class method annotation interface.
 *
 * @package samsonframework\container\annotation
 */
interface MethodInterface extends MethodConfiguratorInterface
{
    /**
     * Convert to class method metadata.
     *
     * @param MethodMetadata $methodMetadata Input metadata
     *
     * @return MethodMetadata Annotation conversion to metadata
     */
    public function toMethodMetadata(MethodMetadata $methodMetadata);
}
