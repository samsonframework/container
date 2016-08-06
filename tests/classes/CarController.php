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
 * @Inject("Car")
 * @Controller()
 * @package samsonframework\di\tests\classes
 */
class CarController
{
    /**
     * @var Car
     */
    protected $car;
}
