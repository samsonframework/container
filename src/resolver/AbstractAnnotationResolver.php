<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:30
 */
namespace samsonframework\container\resolver;

use Doctrine\Common\Annotations\Reader;
use samsonframework\container\metadata\ClassMetadata;

abstract class AbstractAnnotationResolver
{
    /** @var Reader */
    protected $reader;

    /**
     * AnnotationPropertyResolver constructor.
     *
     * @param Reader        $reader
     * @param ClassMetadata $classMetadata
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
}
