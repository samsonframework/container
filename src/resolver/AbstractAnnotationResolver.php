<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:30
 */
namespace samsonframework\container\resolver;

use Doctrine\Common\Annotations\Reader;

/**
 * Abstract annotation resolver.
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
abstract class AbstractAnnotationResolver
{
    /** @var Reader */
    protected $reader;

    /**
     * AnnotationPropertyResolver constructor.
     *
     * @param Reader        $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
}
