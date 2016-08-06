<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 10:56
 */
namespace samsonframework\di\tests;

use PHPUnit\Framework\TestCase;
use samsonframework\container\resolver\AnnotationResolver;

class ApplicationTest extends TestCase
{
    public function testReslover()
    {
        $resolver = new AnnotationResolver(__DIR__.'/cache/');
    }
}
