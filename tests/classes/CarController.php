<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\container\tests\classes;

use samsonframework\container\annotation\Controller;
use samsonframework\container\annotation\Inject;
use samsonframework\container\annotation\Route;

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
     * @Inject("FastDriver")
     */
    protected $fastDriver;

    /**
     * @var DriverInterface
     * @Inject("\samsonframework\container\tests\classes\SlowDriver")
     */
    protected $slowDriver;

    /**
     * @param FastDriver $fastDriver
     * @param SlowDriver $slowDriver
     *
     * @Inject("FastDriver")
     * @Inject("SlowDriver")
     * @Route("/show/", name="car_show")
     *
     * @return FastDriver
     */
    public function showAction(FastDriver $fastDriver, SlowDriver $slowDriver)
    {
        return $fastDriver;
    }
}
