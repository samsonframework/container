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

/**
 * Instance key collection configurator class.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class Instance implements CollectionKeyConfiguratorInterface
{
    use CollectionConfiguratorTrait;

    /**
     * {@inheritdoc}
     */
    public function resolve(array $data)
    {
        return new ClassMetadata();
    }
}
