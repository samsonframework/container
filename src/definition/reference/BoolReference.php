<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

/**
 * Class BoolReference
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class BoolReference implements ReferenceInterface
{
    /** @var bool Value of reference */
    protected $value;

    /**
     * BoolReference constructor
     *
     * @param bool $value
     */
    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function getValue(): bool
    {
        return $this->value;
    }
}
