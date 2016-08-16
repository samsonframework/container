<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 15:46
 */
namespace samsonframework\container\configurator;

/**
 * Abstract injection configurator.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
abstract class InjectableAbstractConfigurator
{
    /** @var string Method argument name */
    protected $argumentName;

    /** @var string Method argument type */
    protected $argumentType;

    /**
     * InjectArgument constructor.
     *
     * @param string $argumentName Injected argument name
     * @param string $argumentType Injected argument type hint
     *
     * @internal param array $valueOrValues
     *
     */
    public function __construct(string $argumentName, string $argumentType)
    {
        $this->argumentName = $argumentName;
        $this->argumentType = $argumentType;
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
