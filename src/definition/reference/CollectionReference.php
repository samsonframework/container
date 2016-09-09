<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

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
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection;
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
