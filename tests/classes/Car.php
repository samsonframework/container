<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:11
 */
namespace samsonframework\di\tests\classes;

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

    /** @var Driver */
    protected $driver;
}
