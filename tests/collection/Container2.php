<?php declare(strict_types = 1);

namespace DI;

/** Application container */
class Container extends \samsonframework\di\Container
{

    public function __construct()
    {
        $this->dependencies = array(
            'samsonframework\container\tests\classes\Car' => array(
                '0' => 'samsonframework\container\tests\classes\SlowDriver',
            ),
            'samsonframework\container\tests\classes\CarService' => array(
                '0' => 'samsonframework\container\tests\classes\Car',
                '1' => 'samsonframework\container\tests\classes\FastDriver',
            ),
            'samsonframework\container\tests\classes\CarServiceWithInterface' => array(
                '0' => 'samsonframework\container\tests\classes\Car',
                '1' => 'samsonframework\container\tests\classes\FastDriver',
            ),
            'samsonframework\container\tests\classes\PublicCar' => array(
                '0' => 'samsonframework\container\tests\classes\SlowDriver',
            ),
        );
        $this->aliases = array();
        $this->services = array(
            '0' => 'samsonframework\container\tests\classes\CarService',
            '1' => 'samsonframework\container\tests\classes\CarServiceWithInterface',
            '2' => 'samsonframework\container\tests\classes\DriverService',
        );
    }

    /** @return \samsonframework\container\tests\classes\Car Get samsonframework\container\tests\classes\Car instance */
    public function getSamsonframeworkContainerTestsClassesCar()
    {
        return $this->container57b07b7c10323('samsonframework\container\tests\classes\Car');
    }

    /** @return \samsonframework\container\tests\classes\CarController Get samsonframework\container\tests\classes\CarController instance */
    public function getSamsonframeworkContainerTestsClassesCarController()
    {
        return $this->container57b07b7c10323('samsonframework\container\tests\classes\CarController');
    }

    /** @return \samsonframework\container\tests\classes\CarService Get car_service instance */
    public function getCarService()
    {
        return $this->container57b07b7c10323('car_service');
    }

    /** @return \samsonframework\container\tests\classes\CarServiceWithInterface Get car_service_with_interface instance */
    public function getCarServiceWithInterface()
    {
        return $this->container57b07b7c10323('car_service_with_interface');
    }

    /** @return \samsonframework\container\tests\classes\DriverService Get driver_service instance */
    public function getDriverService()
    {
        return $this->container57b07b7c10323('driver_service');
    }

    /** @return \samsonframework\container\tests\classes\FastDriver Get samsonframework\container\tests\classes\FastDriver instance */
    public function getSamsonframeworkContainerTestsClassesFastDriver()
    {
        return $this->container57b07b7c10323('samsonframework\container\tests\classes\FastDriver');
    }

    /** @return \samsonframework\container\tests\classes\Leg Get samsonframework\container\tests\classes\Leg instance */
    public function getSamsonframeworkContainerTestsClassesLeg()
    {
        return $this->container57b07b7c10323('samsonframework\container\tests\classes\Leg');
    }

    /** @return \samsonframework\container\tests\classes\PublicCar Get samsonframework\container\tests\classes\PublicCar instance */
    public function getSamsonframeworkContainerTestsClassesPublicCar()
    {
        return $this->container57b07b7c10323('samsonframework\container\tests\classes\PublicCar');
    }

    /** @return \samsonframework\container\tests\classes\SlowDriver Get samsonframework\container\tests\classes\SlowDriver instance */
    public function getSamsonframeworkContainerTestsClassesSlowDriver()
    {
        return $this->container57b07b7c10323('samsonframework\container\tests\classes\SlowDriver');
    }

    /** @return \samsonframework\container\tests\classes\Wheel Get samsonframework\container\tests\classes\Wheel instance */
    public function getSamsonframeworkContainerTestsClassesWheel()
    {
        return $this->container57b07b7c10323('samsonframework\container\tests\classes\Wheel');
    }

    /** {@inheritdoc} */
    protected function logic($dependency)
    {
        return $this->container57b07b7c10323($dependency);
    }

    /** Dependency resolving function */
    protected function container57b07b7c10323($aliasOrClassName)
    {
        static $services = [];

        if ($aliasOrClassName === 'samsonframework\container\tests\classes\Car') {
            return new \samsonframework\container\tests\classes\Car(
                $this->container57b07b7c10323('samsonframework\container\tests\classes\SlowDriver')
            );
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\CarController') {
            $temp = new \samsonframework\container\tests\classes\CarController();
            // Create reflection class for injecting private/protected properties and methods
            $reflectionClass = new \ReflectionClass('samsonframework\container\tests\classes\CarController');

            // Inject public dependency for $car
            $temp->car = $this->container57b07b7c10323('samsonframework\container\tests\classes\Car');
            // Inject public dependency for $fastDriver
            $temp->fastDriver = $this->container57b07b7c10323('samsonframework\container\tests\classes\FastDriver');
            // Inject private dependency for $slowDriver
            $property = $reflectionClass->getProperty('slowDriver');
            $property->setAccessible(true);
            $property->setValue(
                $temp,
                $this->container57b07b7c10323('samsonframework\container\tests\classes\SlowDriver')
            );
            $property->setAccessible(false);

            // Invoke showAction() and pass dependencies(y)
            $temp->showAction(
                $this->container57b07b7c10323('samsonframework\container\tests\classes\FastDriver'),
                $this->container57b07b7c10323('samsonframework\container\tests\classes\SlowDriver')
            );
            // Invoke stopCarAction() and pass dependencies(y)
            $temp->stopCarAction(
                $this->container57b07b7c10323('samsonframework\container\tests\classes\Leg')
            );
            // Invoke setLeg() and pass dependencies(y)
            $method = $reflectionClass->getMethod('setLeg');
            $method->setAccessible(true);
            $method->invoke(
                $temp,
                $this->container57b07b7c10323('samsonframework\container\tests\classes\Leg')
            );

            return $temp;
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\CarService' || $aliasOrClassName === 'car_service') {
            if (!array_key_exists('samsonframework\container\tests\classes\CarService', $services)) {
                return new \samsonframework\container\tests\classes\CarService(
                    $this->container57b07b7c10323('samsonframework\container\tests\classes\Car'),
                    $this->container57b07b7c10323('samsonframework\container\tests\classes\FastDriver')
                );
            }
            return $services['car_service'];
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\CarServiceWithInterface' || $aliasOrClassName === 'car_service_with_interface') {
            if (!array_key_exists('samsonframework\container\tests\classes\CarServiceWithInterface', $services)) {
                $services['car_service_with_interface'] = new \samsonframework\container\tests\classes\CarServiceWithInterface(
                    $this->container57b07b7c10323('samsonframework\container\tests\classes\Car'),
                    $this->container57b07b7c10323('samsonframework\container\tests\classes\FastDriver')
                );
                // Invoke setLeg() and pass dependencies(y)
                $services['car_service_with_interface']->setLeg(
                    $this->container57b07b7c10323('samsonframework\container\tests\classes\Leg')
                );
            }

            return $services['car_service_with_interface'];
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\DriverService' || $aliasOrClassName === 'driver_service') {
            if (!array_key_exists('samsonframework\container\tests\classes\DriverService', $services)) {
                $services['driver_service'] = new \samsonframework\container\tests\classes\DriverService();
                // Create reflection class for injecting private/protected properties and methods
                $reflectionClass = new \ReflectionClass('samsonframework\container\tests\classes\DriverService');

                // Inject private dependency for $car
                $property = $reflectionClass->getProperty('car');
                $property->setAccessible(true);
                $property->setValue(
                    $services['driver_service'],
                    $this->container57b07b7c10323('car_service')
                );
                $property->setAccessible(false);

            }

            return $services['driver_service'];
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\FastDriver') {
            return new \samsonframework\container\tests\classes\FastDriver();
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\Leg') {
            return new \samsonframework\container\tests\classes\Leg();
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\PublicCar') {
            return new \samsonframework\container\tests\classes\PublicCar(
                $this->container57b07b7c10323('samsonframework\container\tests\classes\SlowDriver')
            );
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\SlowDriver') {
            return new \samsonframework\container\tests\classes\SlowDriver();
        } elseif ($aliasOrClassName === 'samsonframework\container\tests\classes\Wheel') {
            return new \samsonframework\container\tests\classes\Wheel();
        }
    }
}
