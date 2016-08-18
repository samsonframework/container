<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\collection\configurator;

use samsonframework\container\collection\CollectionKeyConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Instance/Service properties collection configurator class.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Properties implements CollectionKeyConfiguratorInterface
{
    use CollectionConfiguratorTrait;

    /**
     * {@inheritdoc}
     */
    public function resolve(array $data, ClassMetadata $classMetadata)
    {
        // Parse properties from metadata
        return new PropertyMetadata();
    }
}
