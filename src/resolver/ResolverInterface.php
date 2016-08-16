<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:28.
 */
namespace samsonframework\container\resolver;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Class resolving interface.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
interface ResolverInterface
{
    /**
     * Convert class reflection to internal metadata class.
     *
     * @param mixed  $classData Class information representative
     * @param string $identifier Unique class container identifier
     *
     * @return ClassMetadata Class metadata
     */
    public function resolve($classData, string $identifier = null) : ClassMetadata;
}
