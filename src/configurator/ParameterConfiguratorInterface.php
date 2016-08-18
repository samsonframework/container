<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\configurator;

use samsonframework\container\metadata\ParameterMetadata;

/**
 * Class parameter configurator interface.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
interface ParameterConfiguratorInterface extends ConfiguratorInterface
{
    /**
     * Convert to parameter metadata.
     *
     * @param ParameterMetadata $parameterMetadata Input metadata
     *
     * @return ParameterMetadata
     */
    public function toParameterMetadata(ParameterMetadata $parameterMetadata);
}
