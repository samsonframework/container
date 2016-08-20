<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 14:16
 */
namespace samsonframework\container\tests\collection;

use samsonframework\container\collection\attribute\ClassName;
use samsonframework\container\collection\CollectionParameterResolver;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\ParameterMetadata;
use samsonframework\container\tests\classes\FastDriver;
use samsonframework\container\tests\TestCase;

/**
 * CollectionParameterResolver test class.
 *
 * @author  Vitaly Egorov <egorov@samsonos.com>
 */
class CollectionParameterResolverTest extends TestCase
{
    public function testResolve()
    {
        $classMetadata = new ClassMetadata();
        $classMetadata->className = FastDriver::class;

        $resolver = new CollectionParameterResolver([ClassName::class]);

        $propertyMetadata = $resolver->resolve([
            '@attributes' => [ClassName::KEY => FastDriver::class]
        ], $this->createMock(ParameterMetadata::class));

        static::assertEquals(FastDriver::class, $propertyMetadata->dependency);
    }
}
