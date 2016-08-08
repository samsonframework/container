<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:11
 */
namespace samsonframework\container\tests\classes;

class FastDriver implements DriverInterface
{
    /**
     * @param Leg $leg
     */
    public function stopCar(Leg $leg)
    {
        $leg->pressPedal();
    }
}
