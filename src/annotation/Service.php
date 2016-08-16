<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;

use samsonframework\container\configurator\ServiceConfigurator;

/**
 * Service configurator annotation class.
 * @see    \samsonframework\container\configurator\ServiceConfigurator
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 *
 * @Annotation
 */
class Service extends ServiceConfigurator implements AnnotationConfiguratorInterface
{
    use AnnotationValueTrait;

    /**
     * Service annotation configurator constructor.
     *
     * @param string|array $valueOrValues Service unique name
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($valueOrValues)
    {
        // Parse annotation value
        $serviceNameData = $this->parseAnnotationValue($valueOrValues);

        // Pass to service configurator
        parent::__construct(array_shift($serviceNameData));
    }
}
