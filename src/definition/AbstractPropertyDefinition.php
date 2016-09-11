<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\NullReference;
use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\definition\reference\UndefinedReference;

/**
 * Class AbstractPropertyDefinition
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
abstract class AbstractPropertyDefinition extends AbstractDefinition
{
    /** @var ReferenceInterface */
    protected $dependency;

    /** {@inheritdoc} */
    public function __construct(AbstractDefinition $parentDefinition = null)
    {
        parent::__construct($parentDefinition);

        $this->dependency = new UndefinedReference();
    }

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
