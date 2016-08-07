<?php
declare(strict_types = 1);

/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 13:44
 */
namespace samsonframework\container\annotation;

/**
 *  Annotation with value.
 */
class AnnotationWithValue implements AnnotationInterface
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
    public function __construct(array $valueOrValues)
    {
        if ($this->checkValuePresence($valueOrValues)) {
            // Convert empty values to null
            $value = $valueOrValues['value'] ?? null;

            // Always store array
            $this->collection = is_array($value) ? $value : [$value];
        } else {
            throw new \InvalidArgumentException('Only string or array is allowed');
        }
    }

    /**
     * Check if we received an array with keys.
     *
     * @param array $valueOrValues
     *
     * @return bool True if value key exists
     */
    protected function checkValuePresence(array $valueOrValues)
    {
        return count(array_filter(array_keys($valueOrValues)));
    }
}
