<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 12:39
 */
namespace samsonframework\container;

use samsonframework\container\collection\CollectionClassResolver;

/**
 * XML class metadata collector.
 * Class resolves and collects class metadata from XML string.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class XmlMetadataCollector extends AbstractMetadataCollector
{
    /**
     * {@inheritdoc}
     */
    public function collect($xmlConfig) : array
    {
        $classesMetadata = [];

        // Iterate config and resolve single instance
        foreach ($this->xml2array(new \SimpleXMLElement($xmlConfig)) as $key => $classesArrayData) {
            if ($key === CollectionClassResolver::KEY) {
                /** @var array $classesArrayData */
                foreach ($classesArrayData as $classArrayData) {
                    $classesMetadata[] = $this->resolver->resolve($classArrayData);
                }
            }
        }

        return $classesMetadata;
    }

    /**
     * function xml2array
     *
     * This function is part of the PHP manual.
     *
     * The PHP manual text and comments are covered by the Creative Commons
     * Attribution 3.0 License, copyright (c) the PHP Documentation Group
     *
     * @param \SimpleXMLElement|array $xmlObject
     * @param array                   $out
     *
     * @return array XML converted array
     *
     * @author  k dot antczak at livedata dot pl
     * @date    2011-04-22 06:08 UTC
     * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
     * @license http://www.php.net/license/index.php#doc-lic
     * @license http://creativecommons.org/licenses/by/3.0/
     * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
     */
    protected function xml2array($xmlObject, array $out = []) : array
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = (is_object($node) || is_array($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }
}
