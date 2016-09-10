<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\container\tests\classes\annotation;

use samsonframework\container\definition\analyzer\annotation\annotation\InjectClass;
use samsonframework\container\tests\classes\Car;

class PropClass
{
    /**
     * @InjectClass("samsonframework\container\tests\classes\Car")
     */
    public $car;

    /**
     * PropClass constructor.
     * @InjectClass(name="samsonframework\container\tests\classes\Car")
     */
    public function __construct(Car $car)
    {
    }
}
