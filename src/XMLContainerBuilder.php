<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 13:04
 */
namespace samsonframework\container;

use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\resolver\XmlResolver;
use samsonphp\generator\Generator;

/**
 * XML configuration dependency injection container builder.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class XMLContainerBuilder extends Builder
{
    /**
     * Resolve xml config
     *
     * @param string $xmlConfig
     *
     */
    public function __construct(string $xmlConfig, XmlResolver $classResolver, Generator $generator)
    {
        // Convert xml to array
        $arrayData = $this->xml2array(new \SimpleXMLElement($xmlConfig));
        // Iterate config and resolve single instance
        foreach ($arrayData as $key => $classesArrayData) {
            if ($key === CollectionClassResolver::KEY) {
                foreach ($classesArrayData as $classArrayData) {
                    // Store metadata
                    $classMetadata = $classResolver->resolve($classArrayData);

                    // Store by metadata name as alias
                    $this->classAliases[$classMetadata->name] = $classMetadata->className;

                    // Store class in defined scopes
                    foreach ($classMetadata->scopes as $scope) {
                        $this->scopes[$scope][] = $classMetadata->className;
                    }

                    $this->classMetadata[$classMetadata->className] = $classMetadata;
                }
            }
        }

        parent::__construct($generator, $this->classMetadata);
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
