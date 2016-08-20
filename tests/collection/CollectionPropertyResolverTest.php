<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 14:11
 */
namespace samsonframework\container\tests\collection;

use samsonframework\container\collection\attribute\ClassName;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\TestCase;

/**
 * CollectionParameterResolver test class.
 *
 * @author  Vitaly Egorov <egorov@samsonos.com>
 */
class CollectionPropertyResolverTest extends TestCase
{
    public function testResolve()
    {
        $propertyName = 'car';
        $classMetadata = new ClassMetadata();
        $classMetadata->className = FastDriver::class;

        $resolver = new CollectionPropertyResolver([$this->createMock(ClassName::class)]);

        $classMetadata = $resolver->resolve([
            CollectionPropertyResolver::KEY => [
                $propertyName => [
                    '@attributes' => [ClassName::KEY => FastDriver::class]
                ]
            ]
        ], $classMetadata);

        static::assertArrayHasKey($propertyName, $classMetadata->propertiesMetadata);
    }
}
