<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Vitaly Iegorov
 * Date: 08.08.2016
 * Time: 14:22.
 */
namespace samsonframework\container\metadata;

/**
 * Class MethodMetadata
 * @package samsonframework\container\metadata
 */
class MethodMetadata extends AbstractMetadata
{
    /** @var int Method modifiers */
    public $modifiers = 0;

    /** @var ClassMetadata */
    public $classMetadata;

    /** @var bool Flag that method is public */
    public $isPublic = false;

    /** @var ParameterMetadata[] */
    public $parametersMetadata = [];

    /** @var array ArgumentName => ArgumentType */
    public $dependencies = [];

    /** @var string[string] Class routes collection */
    public $routes = [];

    /**
     * MethodMetadata constructor.
     *
     * @param ClassMetadata $classMetadata
     */
    public function __construct(ClassMetadata $classMetadata)
    {
        $this->classMetadata = $classMetadata;
    }
}
