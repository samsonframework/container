<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

/**
 * Class ServiceReference
 *
 * @package samsonframework\container\definition
 */
class ServiceReference implements ReferenceInterface, ReferenceDependencyInterface
{
    /** @var string Service name */
    protected $name;

    /**
     * ServiceReference constructor.
     *
     * @param string $name Service name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
