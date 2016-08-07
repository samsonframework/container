<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 15:46
 */
namespace samsonframework\container\annotation;

use samsonframework\container\metadata\MethodMetadata;

/**
 * Method argument injection annotation.
 *
 * @Annotation
 */
class InjectArgument extends CollectionValue implements MethodInterface
{
    /** @var string Method argument name */
    protected $argumentName;

    /** @var string Method argument type */
    protected $argumentType;

    /**
     * InjectArgument constructor.
     *
     * @param array $valueOrValues
     */
    public function __construct(array $valueOrValues)
    {
        parent::__construct($valueOrValues);

        // Set data
        foreach ($valueOrValues as $argumentName => $argumentType) {
            $this->argumentName = $argumentName;
            $this->argumentType = $argumentType;
        }
    }

    /** {@inheritdoc} */
    public function toMethodMetadata(MethodMetadata $methodMetadata)
    {
        // Inject only @Inject with value
        if ($this->argumentName !== null && $this->argumentType !== null) {
            $this->argumentName = $this->argumentName;
            $this->argumentType = $this->buildFullClassName($this->argumentType, $methodMetadata->classMetadata->nameSpace);

            $methodMetadata->dependencies[$this->argumentName] = $this->argumentType;
        }
    }

    /**
     * Build full class name.
     *
     * @param string $className Full or short class name
     * @param string $namespace Name space
     *
     * @return string Full class name
     */
    protected function buildFullClassName($className, $namespace)
    {
        // Check if we need to append namespace to dependency
        if ($className !== null && strpos($className, '\\') === false) {
            return $namespace . '\\' . $className;
        }

        return $className;
    }
}
