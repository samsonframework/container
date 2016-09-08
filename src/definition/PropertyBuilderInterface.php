<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Interface PropertyBuilderInterface
 *
 * @package samsonframework\container\definition
 */
interface PropertyBuilderInterface
{
    /**
     * Define property
     *
     * @param ReferenceInterface $dependency
     * @return PropertyDefinition
     */
    public function defineDependency(ReferenceInterface $dependency): PropertyDefinition;

    /**
     * Return to parent definition
     *
     * @return ClassBuilderInterface
     */
    public function end();
}
