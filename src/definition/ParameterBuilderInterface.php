<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\exception\ReferenceNotImplementsException;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\ParameterMetadata;

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
