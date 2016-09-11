<?php declare(strict_types = 1);
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:53
 */
namespace samsonframework\container\definition\parameter;

use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Interface ParameterBuilderInterface
 *
 * @package samsonframework\container\definition\parameter
 */
interface ParameterBuilderInterface
{
    /**
     * Define parameters
     *
     * @param string $name
     * @param ReferenceInterface $reference
     * @return ParameterBuilderInterface
     */
    public function defineParameter(string $name, ReferenceInterface $reference): ParameterBuilderInterface;

    /**
     * Return to parent definition
     *
     * @return DefinitionBuilder
     */
    public function end();
}
