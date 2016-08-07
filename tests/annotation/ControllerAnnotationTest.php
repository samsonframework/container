<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\Controller;
use samsonframework\container\Container;
use samsonframework\container\metadata\ClassMetadata;

class ControllerAnnotationTest extends TestCase
{
    public function testToMetadata()
    {
        $scope = new Controller();
        $metadata = new ClassMetadata();
        $scope->toClassMetadata($metadata);
        static::assertEquals(true, in_array(Container::SCOPE_CONTROLLER, $metadata->scopes));
    }
}
