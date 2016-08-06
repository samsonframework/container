<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 12:24
 */
namespace samsonframework\container\tests\annotation;

use samsonframework\container\annotation\AutoWire;
use samsonframework\container\metadata\ClassMetadata;

class AutowireAnnotationTest extends TestCase
{
    public function testToMetadata()
    {
        $scope = new AutoWire();
        $metadata = new ClassMetadata();
        $scope->toMetadata($metadata);
        static::assertEquals(true, $metadata->autowire);
    }
}
