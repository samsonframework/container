<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:13
 */
namespace samsonframework\container\tests\classes\annotation;

use samsonframework\container\tests\classes\Shoes;

class ProductClass
{
    public function __construct(Shoes $shoes, $val = 'sdf', string $val1 = 'sdfsdfkj', array $arr = [])
    {
    }
}
