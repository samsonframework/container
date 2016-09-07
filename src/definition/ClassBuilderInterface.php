<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

use samsonframework\container\ContainerBuilderInterface;
use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\exception\PropertyDefinitionAlreadyExistsException;
use samsonframework\container\exception\ReferenceNotImplementsException;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\ParameterMetadata;

/**
 * Interface ClassBuilderInterface
 *
 * @package samsonframework\container\definition
 */
interface ClassBuilderInterface
{
    /**
     * Define class constructor parameters
     *
     * @return MethodBuilderInterface
     * @throws MethodDefinitionAlreadyExistsException
     */
    public function defineConstructor(): MethodBuilderInterface;

    /**
     * Define method parameters
     *
     * @param string $methodName
     * @return MethodBuilderInterface
     * @throws MethodDefinitionAlreadyExistsException
     */
    public function defineMethod(string $methodName): MethodBuilderInterface;

    /**
     * Define property
     *
     * @param string $propertyName
     * @return PropertyBuilderInterface
     * @throws PropertyDefinitionAlreadyExistsException
     */
    public function defineProperty(string $propertyName): PropertyBuilderInterface;

    /**
     * Return to parent definition
     *
     * @return DefinitionBuilder
     */
    public function end();

}
