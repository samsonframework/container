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

    /**
     * Convert value to reference instance
     *
     * @param $value
     * @return ReferenceInterface
     * @throws ReferenceNotImplementsException
     */
    public static function convertValueToReference($value)
    {
        $reference = null;
        // Convert type to appropriate reference instance
        if ($value === null) {
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
                'Value "%s" does not have convert implementation', $value
            ));
        }
        return $reference;
    }
}
