<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\configurator;

use samsonframework\container\metadata\MethodMetadata;

/**
 * Class method configurator interface.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
interface MethodConfiguratorInterface extends ConfiguratorInterface
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
