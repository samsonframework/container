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
class CollectionItem
{
    /** @var mixed Key of item */
    protected $key;
    /** @var mixed Value of item */
    protected $value;

    /**
     * CollectionItem constructor.
     *
     * @param ReferenceInterface $key
     * @param ReferenceInterface $value
     */
    public function __construct(ReferenceInterface $key, ReferenceInterface $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Create collection item from php type
     *
     * @param $key
     * @param $value
     * @return CollectionItem
     * @throws ReferenceNotImplementsException
     * @throws \InvalidArgumentException
     */
    public static function create($key, $value)
    {
        return new CollectionItem(
            CollectionReference::convertValueToReference($key),
            CollectionReference::convertValueToReference($value)
        );
    }
}
