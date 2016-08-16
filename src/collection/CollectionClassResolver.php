<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 21:38.
 */
namespace samsonframework\container\collection;

use samsonframework\container\configurator\ConfiguratorInterface;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Array class resolver class.
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class CollectionClassResolver implements CollectionResolverInterface
{
    /** Array key name for searching class cofiguration sections */
    const KEY_CLASS = 'dependencies';
    /** @var array Collection of supported array keys */
    protected $keys = [];

    /**
     * ArrayClassResolver constructor.
     */
    public function __construct()
    {
        // Gather all supported configuration keys
        $this->keys = [];
        foreach (get_declared_classes() as $className) {
            if (in_array(ConfiguratorInterface::class, class_implements($className), true)) {
                $annotationName = substr($className, strrpos($className, '\\') + 1);
                $this->keys[strtolower($annotationName)] = $className;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(array $classDataArray, ClassMetadata $classMetadata)
    {
        foreach ($classDataArray as $item => $configuration) {
            // If we have class resolving key
            foreach ($this->keys as $key => $className) {
                if (array_key_exists($key, $configuration)) {
                    (new $className(['value' => $configuration[$key]]))->toClassMetadata($classMetadata);
                }
            }
        }

        return $classMetadata;
    }

    public function resolveArrayKeys()
    {

    }
}
