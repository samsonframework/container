<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 14:16
 */
namespace samsonframework\container\tests\collection;

use samsonframework\container\collection\attribute\Service;
use samsonframework\container\collection\CollectionMethodResolver;
use samsonframework\container\collection\CollectionParameterResolver;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\TestCase;

/**
 * CollectionMethodResolver test class.
 *
 * @author  Vitaly Egorov <egorov@samsonos.com>
 */
class CollectionMethodResolverTest extends TestCase
{
    public function testResolve()
    {
        $methodName = 'stopCar';
        $serviceName = 'TestService';
        $classMetadata = new ClassMetadata();
        $classMetadata->className = FastDriver::class;

        $resolver = new CollectionMethodResolver(
            [$this->createMock(Service::class)],
            $this->createMock(CollectionParameterResolver::class)
        );

        $classMetadata = $resolver->resolve([
            CollectionMethodResolver::KEY => [
                $methodName => []
            ]
        ], $classMetadata);

        static::assertArrayHasKey($methodName, $classMetadata->methodsMetadata);
    }
}
