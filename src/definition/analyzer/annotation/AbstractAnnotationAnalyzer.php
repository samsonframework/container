<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 17:48
 */
namespace samsonframework\container\definition\analyzer\annotation;

use Doctrine\Common\Annotations\AnnotationReader;

class AbstractAnnotationAnalyzer
{
    /** @var AnnotationReader */
    protected $reader;

    public function __construct(AnnotationReader $annotationReader)
    {
        $this->reader = $annotationReader;
    }
}
