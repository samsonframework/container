<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 13:04
 */
namespace samsonframework\container;

use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\resolver\ResolverInterface;
use samsonframework\filemanager\FileManagerInterface;
use samsonphp\generator\Generator;

/**
 * XML configuration dependency injection container builder.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class XMLContainerBuilder extends ContainerBuilder
{
    /**
     * Resolve xml config
     *
     * @param string $xmlConfig
     *
     */
    public function __construct($xmlConfig, FileManagerInterface $fileManager, ResolverInterface $classResolver, Generator $generator)
    {
        $this->classResolver = $classResolver;

        // Convert xml to array
        $arrayData = $this->xml2array(new \SimpleXMLElement($xmlConfig));
        // Iterate config and resolve single instance
        foreach ($arrayData as $key => $classesArrayData) {
            if ($key === CollectionClassResolver::KEY) {
                foreach ($classesArrayData as $classArrayData) {
                    // Store metadata
                    $classMetadata = $this->classResolver->resolve($classArrayData);
                    $this->classMetadata[$classMetadata->className] = $classMetadata;
                }
            }
        }

        parent::__construct($fileManager, $classResolver, $generator);
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
