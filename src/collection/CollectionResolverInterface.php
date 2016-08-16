<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:38
 */
namespace samsonframework\container\collection;

use samsonframework\container\metadata\AbstractMetadata;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Collection class configurators resolving interface.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
interface CollectionResolverInterface
{
    /**
     * Resolve class annotations.
     *
     * @param array         $classDataArray
     * @param ClassMetadata $classMetadata
     *
     * @return AbstractMetadata
     */
    public function resolve(array $classDataArray, ClassMetadata $classMetadata);
}
