<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;

/**
 * Class AbstractPropertyDefinition
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
abstract class AbstractPropertyDefinition extends AbstractDefinition
{
    /** @var ReferenceInterface */
    protected $dependency;

    /**
     * @param ReferenceInterface $dependency
     * @return AbstractPropertyDefinition
     */
    public function setDependency(ReferenceInterface $dependency): AbstractPropertyDefinition
    {
        $this->dependency = $dependency;

        return $this;
    }

    /**
     * @return ReferenceInterface
     */
    public function getDependency(): ReferenceInterface
    {
        return $this->dependency;
    }
}
