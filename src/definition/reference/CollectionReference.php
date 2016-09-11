<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

use samsonframework\container\definition\builder\exception\ReferenceNotImplementsException;

/**
 * Class CollectionReference
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class CollectionReference implements ReferenceInterface
{
    /** @var array Value of reference */
    protected $collection =  [];

    /**
     * CollectionReference constructor.
     *
     * @param array|CollectionReference $collection
     * @throws \InvalidArgumentException
     * @throws ReferenceNotImplementsException
     */
    public function __construct($collection = null)
    {
        if ($collection) {
            $this->merge($collection);
        }
    }

    /**
     * Merge collections
     *
     * @param $collection
     * @throws \InvalidArgumentException
     * @throws ReferenceNotImplementsException
     */
    public function merge($collection)
    {
        if (is_array($collection)) {
            $this->merge(self::convertArrayToCollection($collection));
        } elseif ($collection instanceof CollectionReference) {
            $this->collection = array_merge($this->collection, $collection->getCollection());
        } else {
            throw new \InvalidArgumentException(sprintf('Wrong type "%s" of collection', gettype($collection)));
        }
    }

    /**
     * Get collection
     *
     * @return CollectionItem[]
     * @throws ReferenceNotImplementsException
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Add item to collection
     *
     * @param CollectionItem $collectionItem
     * @return CollectionReference
     */
    public function addItem(CollectionItem $collectionItem): CollectionReference
    {
        $this->collection[] = $collectionItem;

        return $this;
    }

    /**
     * Convert array to collection reference
     *
     * @param array $array
     * @return CollectionReference
     * @throws ReferenceNotImplementsException
     * @throws \InvalidArgumentException
     */
    public static function convertArrayToCollection(array $array)
    {
        $collection = new CollectionReference();
        foreach ($array as $key => $value) {
            if ($value instanceof CollectionItem) {
                $collection->addItem($value);
            } else {
                // Add item to collection
                $collection->addItem(new CollectionItem(
                    self::convertValueToReference($key),
                    self::convertValueToReference($value)
                ));
            }
        }
        return $collection;
    }

    /**
     * Convert value to reference instance
     *
     * @param $value
     * @return ReferenceInterface
     * @throws \InvalidArgumentException
     * @throws ReferenceNotImplementsException
     */
    public static function convertValueToReference($value)
    {
        // Convert type to appropriate reference instance
        if ($value instanceof ReferenceInterface) {
            $reference = $value;
        } elseif ($value === null) {
            $reference = new NullReference();
        } elseif (is_string($value)) {
            $reference = new StringReference($value);
        } elseif (is_int($value)) {
            $reference = new IntegerReference($value);
        } elseif (is_float($value)) {
            $reference = new FloatReference($value);
        } elseif (is_bool($value)) {
            $reference = new BoolReference($value);
        } elseif (is_array($value)) {
            $reference = new CollectionReference($value);
        } else {
            throw new ReferenceNotImplementsException(sprintf(
                'Value "%s" does not have convert implementation', gettype($value)
            ));
        }
        return $reference;
    }
}
