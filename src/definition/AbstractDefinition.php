<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\definition\reference\ResourceReference;
use samsonframework\container\definition\reference\ServiceReference;
use samsonframework\container\exception\ParentDefinitionNotFoundException;
use samsonframework\container\exception\ReferenceNotImplementsException;

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

    /**
     * Get correct value from reference
     *
     * @param ReferenceInterface $reference
     * @return string
     * @throws ReferenceNotImplementsException
     */
    protected function resolveReferenceValue(ReferenceInterface $reference): string
    {
        if ($reference instanceof ClassReference) {
            return $reference->getClassName();
        } elseif ($reference instanceof ServiceReference) {
            return $reference->getName();
        } elseif ($reference instanceof ResourceReference) {
            return $reference->getValue();
        } else {
            throw new ReferenceNotImplementsException();
        }
    }
}
