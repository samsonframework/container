<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\builder\exception\ReferenceNotImplementsException;
use samsonframework\container\definition\reference\BoolReference;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\FloatReference;
use samsonframework\container\definition\reference\IntegerReference;
use samsonframework\container\definition\reference\NullReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\tests\classes\Car;
use samsonframework\container\tests\TestCaseDefinition;


class CollectionReferenceTest extends TestCaseDefinition
{
    public function testConvertValueToReference()
    {
        static::assertInstanceOf(NullReference::class, CollectionReference::convertValueToReference(null));
        static::assertInstanceOf(IntegerReference::class, CollectionReference::convertValueToReference(22));
        static::assertInstanceOf(FloatReference::class, CollectionReference::convertValueToReference(22.44));
        static::assertInstanceOf(StringReference::class, CollectionReference::convertValueToReference('sdfsdf'));
        static::assertInstanceOf(BoolReference::class, CollectionReference::convertValueToReference(true));
        static::assertInstanceOf(CollectionReference::class, CollectionReference::convertValueToReference(['key' => 'value']));
        static::assertInstanceOf(ClassReference::class, CollectionReference::convertValueToReference(new ClassReference(Car::class)));
    }

    public function testConvertValueToReferenceWrongType()
    {
        $this->expectException(ReferenceNotImplementsException::class);
        CollectionReference::convertValueToReference(function () {});
    }

    public function testConvertArrayToCollection()
    {
        $collection = CollectionReference::convertArrayToCollection(['key' => 444]);
        static::assertInstanceOf(CollectionReference::class, $collection);
        static::assertCount(1, $collection->getCollection());
        static::assertInstanceOf(CollectionItem::class, $collection->getCollection()[0]);
        static::assertInstanceOf(StringReference::class, $collection->getCollection()[0]->getKey());
        static::assertInstanceOf(IntegerReference::class, $collection->getCollection()[0]->getValue());
    }

    public function testConvertArrayToCollectionWithoutKey()
    {
        $collection = CollectionReference::convertArrayToCollection([true]);
        static::assertInstanceOf(IntegerReference::class, $collection->getCollection()[0]->getKey());
        static::assertInstanceOf(BoolReference::class, $collection->getCollection()[0]->getValue());
    }

    public function testConvertArrayToCollectionWithConstant()
    {
        $collection = CollectionReference::convertArrayToCollection(['const' => new ConstantReference('PHP_VERSION')]);
        static::assertInstanceOf(StringReference::class, $collection->getCollection()[0]->getKey());
        static::assertInstanceOf(ConstantReference::class, $collection->getCollection()[0]->getValue());
    }

    public function testConvertArrayToCollectionManyItems()
    {
        $collection = CollectionReference::convertArrayToCollection([
            'const' => new ConstantReference('PHP_VERSION'), '333'
        ]);
        static::assertInstanceOf(StringReference::class, $collection->getCollection()[0]->getKey());
        static::assertInstanceOf(ConstantReference::class, $collection->getCollection()[0]->getValue());
        static::assertInstanceOf(IntegerReference::class, $collection->getCollection()[1]->getKey());
        static::assertInstanceOf(StringReference::class, $collection->getCollection()[1]->getValue());
    }

    public function testConvertArrayToCollectionCollectionItem()
    {
        $collection = CollectionReference::convertArrayToCollection([new CollectionItem(new ConstantReference('PHP_VERSION'), new ConstantReference('PHP_VERSION'))]);
        static::assertInstanceOf(ConstantReference::class, $collection->getCollection()[0]->getKey());
        static::assertInstanceOf(ConstantReference::class, $collection->getCollection()[0]->getValue());
    }

    public function testConvertArrayToCollectionAnotherCollection()
    {
        $collection = CollectionReference::convertArrayToCollection([new CollectionReference([444])]);
        static::assertInstanceOf(CollectionReference::class, $collection->getCollection()[0]->getValue());
    }

    public function testAddItem()
    {
        $collection = new CollectionReference();
        $collection->addItem(CollectionItem::create('key', 'value'));

        static::assertInstanceOf(CollectionItem::class, $collection->getCollection()[0]);
        static::assertEquals('key', $collection->getCollection()[0]->getKey()->getValue());
        static::assertEquals('value', $collection->getCollection()[0]->getValue()->getValue());
    }

    public function testAddManyItems()
    {
        $collection = new CollectionReference();
        $collection->addItem(CollectionItem::create('key', 'value'));
        $collection->addItem(CollectionItem::create(333, 'value'));

        static::assertInstanceOf(CollectionItem::class, $collection->getCollection()[0]);
        static::assertEquals('key', $collection->getCollection()[0]->getKey()->getValue());
        static::assertEquals('value', $collection->getCollection()[0]->getValue()->getValue());
        static::assertEquals(333, $collection->getCollection()[1]->getKey()->getValue());
        static::assertEquals('value', $collection->getCollection()[1]->getValue()->getValue());
    }

    public function testGetCollection()
    {
        $collection = new CollectionReference();
        $collection->addItem(CollectionItem::create('key', 'value'));

        static::assertCount(1, $collection->getCollection());
    }

    public function testMergeWithCollection()
    {
        $collection1 = new CollectionReference();
        $collection1->addItem(CollectionItem::create('key1', 'value'));
        $collection2 = new CollectionReference();
        $collection2->addItem(CollectionItem::create('key2', 'value'));

        $collection1->merge($collection2);

        static::assertCount(2, $collection1->getCollection());
        static::assertInstanceOf(StringReference::class, $collection1->getCollection()[1]->getKey());
    }

    public function testMergeWithArray()
    {
        $collection1 = new CollectionReference();
        $collection1->addItem(CollectionItem::create('key1', 'value'));

        $collection1->merge(['key2' => 'value']);

        static::assertCount(2, $collection1->getCollection());
        static::assertInstanceOf(StringReference::class, $collection1->getCollection()[1]->getKey());
    }

    public function testMergeWrongArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $collection = new CollectionReference();
        $collection->merge(444);
    }

    public function testConstructor()
    {
        $collection1 = new CollectionReference([CollectionItem::create('key1', 'value')]);

        static::assertCount(1, $collection1->getCollection());
        static::assertInstanceOf(StringReference::class, $collection1->getCollection()[0]->getKey());
    }
}
