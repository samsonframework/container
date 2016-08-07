<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\container\tests\classes;

use samsonframework\container\annotation\Inject;
use samsonframework\container\annotation\InjectArgument;
use samsonframework\container\annotation\Service;

/**
 * Car service class.
 *
 * @Service("car_service")
 */
class CarService
{
    /**
     * @var Car
     * @Inject
     */
    protected $car;

    /**
     * CarService constructor.
     *
     * @param Car $car
     * @InjectArgument(car="Car")
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }
}
