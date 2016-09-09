<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

/**
 * Class ConstantReference
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class ConstantReference implements ReferenceInterface
{
    /** @var string Value of reference */
    protected $value;

    /**
     * StringReference constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
