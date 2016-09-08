<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\exception\ParentDefinitionNotFoundException;

/**
 * Class AbstractDefinition
 *
 * @package samsonframework\container\definition
 */
abstract class AbstractDefinition
{
    /** @var AbstractDefinition Parent definition */
    protected $parentDefinition;

    /**
     * AbstractDefinition constructor.
     *
     * @param AbstractDefinition $parentDefinition
     */
    public function __construct(AbstractDefinition $parentDefinition = null)
    {
        $this->parentDefinition = $parentDefinition;
    }

    /**
     * End definition and get control to parent
     *
     * @return AbstractDefinition
     * @throws ParentDefinitionNotFoundException
     */
    public function end(): AbstractDefinition
    {
        return $this->getParentDefinition();
    }

    /**
     * Get parent definition
     *
     * @return AbstractDefinition
     * @throws ParentDefinitionNotFoundException
     */
    public function getParentDefinition(): AbstractDefinition
    {
        if ($this->parentDefinition === null) {
            throw new ParentDefinitionNotFoundException();
        }

        return $this->parentDefinition;
    }
}
