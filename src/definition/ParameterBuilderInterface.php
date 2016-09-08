<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Interface ParameterBuilderInterface
 *
 * @package samsonframework\container\definition
 */
interface ParameterBuilderInterface
{
    /**
     * Define method parameter
     *
     * @param ReferenceInterface $dependency
     * @return ParameterBuilderInterface
     */
    public function defineDependency(ReferenceInterface $dependency): ParameterBuilderInterface;

    /**
     * Return to parent definition
     *
     * @return MethodBuilderInterface
     */
    public function end();
}
