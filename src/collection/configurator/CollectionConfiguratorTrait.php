<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 17.08.16 at 09:19
 */
namespace samsonframework\container\collection\configurator;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Class CollectionConfiguratorTrait
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
trait CollectionConfiguratorTrait
{
    /**
     * Get collection configurator marker.
     *
     * @return string Collection configurator key
     */
    public static function getMarker()
    {
        // Get collection configurator key as its lowered class name
        return strtolower(substr(get_called_class(), strrpos(get_called_class(), '\\') + 1));
    }

    /**
     * Generic collection configurator data resolver.
     *
     * @param mixed $data Data for resolving
     */
    public function resolve(array $data, ClassMetadata $classMetadata)
    {

    }
}
