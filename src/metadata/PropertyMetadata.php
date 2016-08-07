<?php
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
    public $name;

    /** @var int Property modifiers */
    public $modifiers;

    /** @var string Property typeHint from typeHint hint */
    public $typeHint;

    /** @var string Property real typeHint */
    public $dependency;

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
