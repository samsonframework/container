<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */

namespace samsonframework\di\annotation;

/**
 * Class Route.
 *
 * @Annotation
 */
class Route implements MethodAnnotation
{
    const METHOD_ALIAS = 'route';

    /**
     * @var string Path to route
     */
    public $path;

    /**
     * Route constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Get method alias.
     *
     * @return string
     */
    public function getMethodAlias()
    {
        return self::METHOD_ALIAS;
    }

    /**
     * Convert annotation to method metadata.
     *
     * @return array
     */
    public function convertToMetadata()
    {
        return ['path' => $this->path['value']];
    }
}
