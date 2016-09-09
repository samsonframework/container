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
class CollectionItem
{
    /** @var mixed Key of item */
    protected $key;
    /** @var mixed Value of item */
    protected $value;

    /**
     * CollectionItem constructor.
     *
     * @param $key
     * @param $value
     */
    public function __construct($key, $value)
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
}
