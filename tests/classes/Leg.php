<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 08.08.16 at 18:15
 */
namespace samsonframework\container\tests\classes;

/**
 * Class Leg
 * @package samsonframework\container\tests\classes
 */
class Leg
{
    /** @var Shoes */
    public $shoes;

    /**
     * @param Shoes $shoes
     */
    public function pressPedal(Shoes $shoes)
    {
        $this->shoes = $shoes;
    }
}