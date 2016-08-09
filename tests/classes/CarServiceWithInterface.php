<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\container\tests\classes;

use samsonframework\container\annotation\InjectArgument;
use samsonframework\container\annotation\Service;

/**
 * Car service class.
 *
 * @Service("car_service_with_interface")
 */
class CarServiceWithInterface
{
    /** @var Car */
    protected $car;

    /** @var DriverInterface */
    protected $driver;

    /** @var  Leg */
    protected $leg;

    /**
     * CarService constructor.
     *
     * @param Car             $car
     * @param DriverInterface $driver
     *
     * @InjectArgument(car="Car")
     * @InjectArgument(driver="FastDriver")
     */
    public function __construct(Car $car, DriverInterface $driver)
    {
        $this->car = $car;
        $this->driver = $driver;
    }

    /**
     * Set leg
     *
     * @InjectArgument(leg="Leg")
     */
    public function setLeg(Leg $leg)
    {
        $this->leg = $leg;
    }
}
