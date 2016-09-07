<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

/**
 * Class ResourceReference
 *
 * @package samsonframework\container\definition
 */
class ResourceReference implements ReferenceInterface
{
    /** @var mixed Value of reference */
    protected $value;

    /**
     * ResourceReference constructor.
     *
     * @param string $value Value of reference
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
