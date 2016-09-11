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
     * @param array $collection
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param bool $convertToCollectionItem
     * @return array
     * @throws ReferenceNotImplementsException
     */
    public function getCollection(bool $convertToCollectionItem = false): array
    {
        // When need convert simple array to collection item collection
        if ($convertToCollectionItem) {
            $collection = [];
            foreach ($this->collection as $key => $value) {
                if ($value instanceof CollectionItem) {
                    $collection[$key] = $value;
                } else {
                    $collection[$key] = new CollectionItem(
                        CollectionItem::convertValueToReference($key),
                        CollectionItem::convertValueToReference($value)
                    );
                }
            }
        } else {
            $collection = $this->collection;
        }
        return $collection;
    }

    /**
     * Add item to collection
     *
     * @param $value
     * @param null $key
     * @return CollectionReference
     */
    public function addItem($value, $key = null): CollectionReference
    {
        // If its reference then create collection item
        if ($key instanceof ReferenceInterface) {
            $this->collection[] = new CollectionItem($key, $value);
        } elseif ($key) {
            $this->collection[$key] = $value;
        } else {
            $this->collection[] = $value;
        }

        return $this;
    }
}
