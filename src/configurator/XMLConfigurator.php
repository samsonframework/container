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
    public function configure(string $configuration)
    {
        $configString = file_get_contents(__DIR__ . '/../../../app/config/prod.xml');
        $config = new \SimpleXMLElement($configString);

        // Find all configuration classes
        $configData = [];
        foreach ($config->container as $service) {
            foreach ($service as $serviceName => $configuration) {
                $configData[$serviceName] = (array)$configuration;
            }
        }
    }
}
