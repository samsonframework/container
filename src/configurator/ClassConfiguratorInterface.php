<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 14:37
 */
namespace samsonframework\container\configurator;

use samsonframework\container\metadata\ClassMetadata;

/**
 * Class configurator interface.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
interface ClassConfiguratorInterface extends ConfiguratorInterface
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
