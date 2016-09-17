<?php declare(strict_types=1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 17:48
 */
namespace samsonframework\container\definition\analyzer\annotation;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Class AbstractAnnotationAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class AbstractAnnotationAnalyzer
{
    /** @var AnnotationReader */
    protected $reader;

    /**
     * AbstractAnnotationAnalyzer constructor.
     *
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->reader = $annotationReader;
    }
}
