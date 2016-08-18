<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:38
 */
namespace samsonframework\container\collection;

use samsonframework\container\metadata\ParameterMetadata;

/**
 * Collection parameter configurators resolving interface.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
interface CollectionParameterResolverInterface
{
    /**
     * Resolve parameter.
     *
     * @param array             $parameterDataArray
     * @param ParameterMetadata $parameterMetadata
     *
     * @return ParameterMetadata
     */
    public function resolve(array $parameterDataArray, ParameterMetadata $parameterMetadata);
}
