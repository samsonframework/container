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
 * Wheel Controller class.
 */
class WheelController
{
    /**
     * @var Car
     */
    public $car;

    /**
     * @var bool
     */
    public $defaultValue = true;

    /**
     * @param FastDriver $fastDriver
     * @param SlowDriver $slowDriver
     * @param array $params
     * @param string $id
     */
    public function __construct(DriverInterface $fastDriver, SlowDriver $slowDriver, Car $car, array $params, string $id)
    {
    }

    /**
     * @param Leg $leg
     */
    protected function setLeg(Leg $leg)
    {
        $this->leg = $leg;
    }

    protected function setDriver(string $leg = 'leg')
    {
    }
}
