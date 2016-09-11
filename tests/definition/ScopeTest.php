<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\scope\ControllerScope;
use samsonframework\container\definition\scope\ServiceScope;
use samsonframework\container\tests\TestCaseDefinition;


class ScopeTest extends TestCaseDefinition
{
    public function testScopeId()
    {
        static::assertEquals('service', ServiceScope::getId());
        static::assertEquals('controller', ControllerScope::getId());
    }
}
