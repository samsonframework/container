<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

/**
 * Interface MethodBuilderInterface
 *
 * @package samsonframework\container\definition
 */
interface MethodBuilderInterface
{
    /**
     * Define method parameters
     *
     * @param string $parameterName
     * @return ParameterBuilderInterface
     */
    public function defineParameter($parameterName): ParameterBuilderInterface;

    /**
     * Return to parent definition
     *
     * @return ClassBuilderInterface
     */
    public function end();
}
