<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 14.08.16 at 15:55
 */
namespace samsonframework\container\resolver;

use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\collection\CollectionMethodResolver;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\metadata\ClassMetadata;

/**
 * XML dependency injection container configuration.
 *
 * @author Vitaly Iegorov <egorov@samsonos.com>
 * @author Ruslan Molodyko  <molodyko@samsonos.com>
 */
class XmlResolver implements ResolverInterface
{
    /** @var CollectionClassResolver */
    protected $classResolver;

    /** @var CollectionPropertyResolver */
    protected $propertyResolver;

    /** @var CollectionMethodResolver */
    protected $methodResolver;

    /**
     * AnnotationResolver constructor.
     *
     * @param CollectionClassResolver $classResolver
     * @param CollectionPropertyResolver $propertyResolver
     * @param CollectionMethodResolver $methodResolver
     */
    public function __construct(
        CollectionClassResolver $classResolver,
        CollectionPropertyResolver $propertyResolver,
        CollectionMethodResolver $methodResolver
    ) {
        $this->classResolver = $classResolver;
        $this->propertyResolver = $propertyResolver;
        $this->methodResolver = $methodResolver;
    }

    /**
     * Resolve xml config
     *
     * @param string $xmlConfig
     * @return array
     */
    public function resolveConfig($xmlConfig) : array
    {
        $listClassMetadata = [];
        // Convert xml to array
        $arrayData = $this->xml2array(new \SimpleXMLElement($xmlConfig));
        // Iterate config and resolve single instance
        foreach ($arrayData as $key => $classesArrayData) {
            if ($key === CollectionClassResolver::KEY) {
                foreach ($classesArrayData as $classArrayData) {
                    // Store metadata
                    $listClassMetadata[] = $this->resolve($classArrayData);
                }
            }
        }
        return $listClassMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($classArrayData, string $identifier = null) : ClassMetadata
    {
        // Create and fill class metadata base fields
        $classMetadata = new ClassMetadata();

        // Resolve class definition annotations
        $this->classResolver->resolve($classArrayData, $classMetadata);
        // Resolve class properties annotations
        $this->propertyResolver->resolve($classArrayData, $classMetadata);
        // Resolve class methods annotations
        $this->methodResolver->resolve($classArrayData, $classMetadata);

        return $classMetadata;
    }

    /**
     * function xml2array
     *
     * This function is part of the PHP manual.
     *
     * The PHP manual text and comments are covered by the Creative Commons
     * Attribution 3.0 License, copyright (c) the PHP Documentation Group
     *
     * @author  k dot antczak at livedata dot pl
     * @date    2011-04-22 06:08 UTC
     * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
     * @license http://www.php.net/license/index.php#doc-lic
     * @license http://creativecommons.org/licenses/by/3.0/
     * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
     */
    protected function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = (is_object($node) || is_array($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }
}
