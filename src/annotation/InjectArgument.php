<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 15:46
 */
namespace samsonframework\container\annotation;

use samsonframework\container\configurator\InjectableArgumentConfigurator;

/**
 * Method argument injection annotation.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 *
 * @Annotation
 */
class InjectArgument extends InjectableArgumentConfigurator
{
    use AnnotationValueTrait;

    /**
     * InjectArgument constructor.
     *
     * @param array $valueOrValues
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $valueOrValues)
    {
        // Parse argument injection data
        list($argumentName, $argumentType) = each($this->parseAnnotationValue($valueOrValues));

        // Pass to injectable argument configurator
        parent::__construct($argumentName, $argumentType);
    }
}
