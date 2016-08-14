<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 14.08.16 at 15:55
 */
namespace samsonframework\container\configurator;

/**
 * XML dependency injection container configuration.
 * @author Vitaly Iegorov <egorov@samsonos.com>
 * @author Ruslan Molodyko  <molodyko@samsonos.com>
 */
class XMLConfigurator
{
    public function configure(string $inputConfiguration) : array
    {
        return $this->xml2array(new \SimpleXMLElement($inputConfiguration));
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
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }
}
