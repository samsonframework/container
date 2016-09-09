<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\PropertyDefinitionAlreadyExistsException;

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
     * @throws MethodDefinitionAlreadyExistsException, ParentDefinitionNotFoundException
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
     * Define is this class is singleton singleton
     *
     * @return ClassBuilderInterface
     */
    public function defineIsSingleton(): ClassBuilderInterface;

    /**
     * Define is this class is prototype object
     *
     * @return ClassBuilderInterface
     */
    public function defineIsPrototype(): ClassBuilderInterface;

    /**
     * Return to parent definition
     *
     * @return DefinitionBuilder
     */
    public function end();

}
