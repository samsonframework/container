<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\container\tests\classes;

use samsonframework\container\annotation\Controller;
use samsonframework\container\annotation\Inject;
use samsonframework\container\annotation\InjectArgument;
use samsonframework\container\annotation\Route;
use samsonframework\container\annotation\Scope;

/**
 * Car Controller class.
 *
 * @Controller
 * @Route("/car")
 * @Scope("cars")
 */
class CarController
{
    /**
     * @var Car
     * @Inject
     */
    public $car;

    /**
     * @var DriverInterface
     * @Inject("FastDriver")
     */
    public $fastDriver;

    /**
     * @var DriverInterface
     * @Inject("\samsonframework\container\tests\classes\SlowDriver")
     */
    public $slowDriver;

    /**
     * @Route("/show/", name="car_show")
     * @InjectArgument(fastDriver="FastDriver")
     * * @InjectArgument(slowDriver="SlowDriver")
     * @return FastDriver
     */
    public function showAction(FastDriver $fastDriver, SlowDriver $slowDriver)
    {
        return $this->fastDriver;
    }

    /**
     * @param Leg $leg
     * @InjectArgument(leg="Leg")
     */
    public function stopCarAction(Leg $leg)
    {
        $this->fastDriver->stopCar($leg);
    }
}
