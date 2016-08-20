<?php declare(strict_types = 1);
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
     * @var array
     */
    private $array;
    /**
     * @var string
     */
    private $string;

    /**
     * FastDriver constructor.
     *
     * @param Leg    $leg
     * @param array  $array
     * @param string $string
     *
     * @internal param $
     */
    public function __construct(Leg $leg, array $array, string $string)
    {
        $this->leg = $leg;
        $this->array = $array;
        $this->string = $string;
    }

    /**
     * @param Leg $leg
     */
    public function stopCar(Leg $leg)
    {

    }

    /**
     * @param Leg $leg
     */
    protected function stopHiddenCar(Leg $leg)
    {

    }
}
