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
     * Convert class data to internal class metadata.
     *
     * @param mixed         $classData     Class information representative
     * @param ClassMetadata $classMetadata Previously existent metadata
     *
     * @return ClassMetadata Class metadata
     */
    public function resolve($classData, ClassMetadata $classMetadata = null) : ClassMetadata;
}
