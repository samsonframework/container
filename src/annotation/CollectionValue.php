<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 13:44
 */
namespace samsonframework\container\annotation;

/**
 * Generic collection value annotation.
 *
 * @package samsonframework\container\annotation
 */
class CollectionValue
{
    /** @var string[] Collection of class collection */
    protected $collection = [];

    /**
     * Scope constructor.
     *
     * @param array $valueOrValues Class collection
     *
     * @throws \InvalidArgumentException Thrown when neither string nor string[] is passed
     */
    public function __construct($valueOrValues)
    {
        if (is_array($valueOrValues) && array_key_exists('value', $valueOrValues)) {
            // Convert empty values to null
            $value = $valueOrValues['value'] !== '' ? $valueOrValues['value'] : null;

            if (!is_array($value) && !is_string($value) && $value !== null) {
                throw new \InvalidArgumentException('Only string or array is allowed');
            }

            // Always store array
            $this->collection = is_array($value) ?: [$value];
        }
    }
}
