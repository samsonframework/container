<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 22:22.
 */
namespace samsonframework\container\metadata;

/**
 * Class property metadata.
 */
class PropertyMetadata
{
    /** @var string Property name */
    public $name = '';

    /** @var int Property modifiers */
    public $modifiers = 0;

    /** @var bool Flag that property is public */
    public $isPublic = false;

    /** @var string Property typeHint from typeHint hint */
    public $typeHint = '';

    /** @var string Property real typeHint */
    public $dependency = '';

    /** @var string Property value */
    public $value;

    /** @var ClassMetadata */
    public $classMetadata;

    /**
     * PropertyMetadata constructor.
     *
     * @param ClassMetadata $classMetadata
     */
    public function __construct(ClassMetadata $classMetadata)
    {
        $this->classMetadata = $classMetadata;
    }
}
