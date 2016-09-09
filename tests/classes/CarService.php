<?php declare(strict_types = 1);
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
 * @Service("car_service")
 */
class CarService
{
    /** @var Car */
    protected $car;

    /** @var DriverInterface */
    protected $driver;

    /**
     * CarService constructor.
     *
     * @param Car $car
     * @InjectArgument(car="Car")
     * @InjectArgument(driver="FastDriver")
     */
    public function __construct(Car $car, DriverInterface $driver)
    {
        $this->car = $car;
        $this->driver = $driver;
    }
}
