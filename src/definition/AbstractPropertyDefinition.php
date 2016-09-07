<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Class AbstractPropertyDefinition
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
abstract class AbstractPropertyDefinition extends AbstractDefinition
{
    /** @var ReferenceInterface */
    protected $dependency;
    /** @var int Property modifiers */
    public $modifiers = 0;
    /** @var bool Flag that property is public */
    public $isPublic = false;
    /** @var string Property typeHint from typeHint hint */
    public $typeHint = '';
    /** @var mixed Property value */
    public $value;

    /**
     * @return int
     */
    public function getModifiers(): int
    {
        return $this->modifiers;
    }

    /**
     * @param int $modifiers
     * @return AbstractPropertyDefinition
     */
    public function setModifiers(int $modifiers): AbstractPropertyDefinition
    {
        $this->modifiers = $modifiers;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param boolean $isPublic
     * @return AbstractPropertyDefinition
     */
    public function setIsPublic(bool $isPublic): AbstractPropertyDefinition
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypeHint(): string
    {
        return $this->typeHint;
    }

    /**
     * @param string $typeHint
     * @return AbstractPropertyDefinition
     */
    public function setTypeHint(string $typeHint): AbstractPropertyDefinition
    {
        $this->typeHint = $typeHint;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return AbstractPropertyDefinition
     */
    public function setValue($value): AbstractPropertyDefinition
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param ReferenceInterface $dependency
     * @return AbstractPropertyDefinition
     */
    public function setDependency(ReferenceInterface $dependency): AbstractPropertyDefinition
    {
        $this->dependency = $dependency;

        return $this;
    }

    /**
     * @return ReferenceInterface
     */
    public function getDependency(): ReferenceInterface
    {
        return $this->dependency;
    }
}
