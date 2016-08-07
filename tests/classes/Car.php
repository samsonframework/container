<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:11
 */
namespace samsonframework\container\tests\classes;

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

    /** @var FastDriver */
    protected $driver;
}
