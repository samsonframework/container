<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 14:16
 */
namespace samsonframework\container\tests\collection;

use samsonframework\container\collection\attribute\Service;
use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\TestCase;

/**
 * CollectionClassResolver test class.
 *
 * @author  Vitaly Egorov <egorov@samsonos.com>
 */
class CollectionClassResolverTest extends TestCase
{
    public function testResolve()
    {
        $serviceName = 'TestService';
        $resolver = new CollectionClassResolver([Service::class]);

        $classMetadata = $resolver->resolve(['@attributes' => [Service::KEY => $serviceName]], new ClassMetadata());

        static::assertEquals($serviceName, $classMetadata->name);
    }
}
