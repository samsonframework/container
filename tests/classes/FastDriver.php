<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:11
 */
namespace samsonframework\container\tests\classes;

use samsonframework\container\annotation\InjectArgument;

class FastDriver implements DriverInterface
{
    /** @var Leg */
    protected $leg;

    /**
     * FastDriver constructor.
     *
     * @param Leg $leg
     * @InjectArgument(leg="Leg")
     */
    public function __construct(Leg $leg)
    {
        $this->leg = $leg;
    }

    /**
     * @param Leg $leg
     */
    public function stopCar(Leg $leg)
    {
        $leg->pressPedal();
    }
}
