<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\container\tests\classes;

use samsonframework\container\annotation\Controller;
use samsonframework\container\annotation\Inject;

/**
 * Car Controller class.
 * @Controller
 * @package samsonframework\di\tests\classes
 */
class CarController
{
    /**
     * @var Car
     * @Inject
     */
    protected $car;

    /**
     * @var DriverInterface
     * @Inject
     */
    protected $unknownDriver;

    /**
     * @var DriverInterface
     * @Inject("Car")
     */
    protected $wrongDriver;

    /**
     * @var DriverInterface
     * @Inject("FastDriver")
     */
    protected $fastDriver;

    /**
     * @var DriverInterface
     * @Inject("\samsonframework\container\tests\classes\SlowDriver")
     */
    protected $slowDriver;
}
