<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 17.08.16 at 09:19
 */
namespace samsonframework\container\collection;

/**
 * Class CollectionConfiguratorTrait
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
trait CollectionConfiguratorTrait
{
    /**
     * Get collection configurator key.
     *
     * @return string Collection configurator key
     */
    public static function getKey()
    {
        // Get collection configurator key as its lowered class name
        return strtolower(substr(get_called_class(), strrpos(get_called_class(), '\\') + 1));
    }
}
