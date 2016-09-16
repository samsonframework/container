<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\reference;

/**
 * Class ClassReference
 *
 * @package samsonframework\container\definition
 */
class ClassReference implements ReferenceInterface, ReferenceDependencyInterface
{
    /** @var string Class name */
    protected $className;

    /**
     * ClassReference constructor.
     *
     * @param string $className Class name
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }
}
