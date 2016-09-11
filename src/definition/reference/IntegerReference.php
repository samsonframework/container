<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

/**
 * Class IntegerReference
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class IntegerReference implements ReferenceInterface
{
    /** @var int Value of reference */
    protected $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
