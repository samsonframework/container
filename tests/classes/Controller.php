<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\di\tests\classes;

use samsonframework\di\annotation\Inject;
use samsonframework\di\annotation\Controller;

/**
 * Class Controller
 * @Controller()
 * @package samsonframework\di\tests\classes
 */
class Controller
{
    /**
     * @var Car
     * @Inject()
     */
    protected $car;
}
