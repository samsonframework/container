<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:11
 */
namespace samsonframework\container\tests\classes;

use samsonframework\container\annotation\InjectArgument;

class Car
{
    /** @var Wheel */
    protected $frontLeftWheel;
    /** @var Wheel */
    protected $frontRightWheel;
    /** @var Wheel */
    protected $backLeftWheel;
    /** @var Wheel */
    protected $backRightWheel;

    /** @var DriverInterface */
    protected $driver;

    /**
     * Car constructor.
     *
     * @param DriverInterface $driver
     *
     * @InjectArgument(driver="SlowDriver")
     */
    public function __construct(DriverInterface $driver)
    {

    }
}
