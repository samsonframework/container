<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 15:46
 */
namespace samsonframework\container\configurator;

use samsonframework\container\metadata\MethodMetadata;

/**
 * Method argument injection configurator.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class InjectableArgumentConfigurator extends InjectableAbstractConfigurator implements MethodConfiguratorInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function toMethodMetadata(MethodMetadata $methodMetadata)
    {
        // Check for argument name input and validity
        if (!$this->checkArgumentExists($this->argumentName, $methodMetadata)) {
            throw new \InvalidArgumentException(
                '@InjectArgument argument "'
                . $methodMetadata->classMetadata->className . '::'
                . $methodMetadata->name . ' '
                . $this->argumentName . '" does not exists'
            );
        }

        // Check for type input
        if ($this->argumentType === null) {
            throw new \InvalidArgumentException(
                '@InjectArgument argument "'
                . $methodMetadata->classMetadata->className . '::'
                . $methodMetadata->name . ' '
                . $this->argumentName . '" type not specified'
            );
        }

        // Store dependency with fully qualified type name
        $methodMetadata->dependencies[$this->argumentName] = $this->buildFullClassName(
            $this->argumentType,
            $methodMetadata->classMetadata->nameSpace
        );
    }

    /**
     * Check method argument existance.
     *
     * @param string         $argument
     * @param MethodMetadata $methodMetadata
     *
     * @return bool True if @InjectArgument argument name is valid
     */
    protected function checkArgumentExists(string $argument, MethodMetadata $methodMetadata) : bool
    {
        return $argument !== null && array_key_exists($argument, $methodMetadata->parametersMetadata);
    }
}
