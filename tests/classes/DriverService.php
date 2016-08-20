<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\container\tests\classes;

use samsonframework\container\annotation\Inject;
use samsonframework\container\annotation\Service;

/**
 * Driver service class.
 * @Service("driver_service")
 * @package samsonframework\di\tests\classes
 */
class DriverService
{
    /**
     * @var CarService
     * @Inject("car_service")
     */
    public $car;
}
