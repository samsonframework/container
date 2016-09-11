<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 14:38
 */
namespace samsonframework\container\tests\definition\definition;

use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\tests\TestCaseDefinition;


class CollectionItemTest extends TestCaseDefinition
{
    public function testCreation()
    {
        $item = CollectionItem::create('key', 'value');

        static::assertInstanceOf(StringReference::class, $item->getKey());
        static::assertInstanceOf(StringReference::class, $item->getValue());
    }

    public function testConstructor()
    {
        $item = new CollectionItem(new StringReference('key'), new StringReference('value'));

        static::assertInstanceOf(StringReference::class, $item->getKey());
        static::assertInstanceOf(StringReference::class, $item->getValue());
    }
}
