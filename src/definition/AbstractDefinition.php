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
     * End definition and get control to parent
     *
     * @return AbstractDefinition
     */
    public function end() {
        return $this->parentDefinition;
    }

    /**
     * Get correct value from reference
     *
     * @param ReferenceInterface $reference
     * @return mixed|string
     * @throws ReferenceNotImplementsException
     */
    protected function resolveReference(ReferenceInterface $reference)
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
