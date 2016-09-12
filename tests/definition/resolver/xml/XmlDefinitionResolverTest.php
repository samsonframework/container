<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 12.09.2016
 * Time: 6:34
 */
namespace samsonframework\container\tests\definition\definition\resolver\xml;

use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\definition\parameter\ParameterBuilder;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\CollectionItem;
use samsonframework\container\definition\reference\CollectionReference;
use samsonframework\container\definition\reference\ConstantReference;
use samsonframework\container\definition\reference\ParameterReference;
use samsonframework\container\definition\reference\StringReference;
use samsonframework\container\definition\resolver\xml\XmlDefinitionResolver;
use samsonframework\container\definition\resolver\xml\XmlParameterResolver;
use samsonframework\container\definition\resolver\xml\XmlResolver;
use samsonframework\container\tests\classes\annotation\XmlProductClass;
use samsonframework\container\tests\classes\Shoes;
use samsonframework\container\tests\classes\SlowDriver;
use samsonframework\container\tests\TestCaseDefinition;

class XmlDefinitionResolverTest extends TestCaseDefinition
{
    public function testCollectionResolve()
    {
//        $resolver = new XmlDefinitionResolver();
//        $resolver->resolveCollection($code);

    }

    public function testXml()
    {
        $builder = new DefinitionBuilder(new ParameterBuilder());
        $builder
            ->addDefinition(\PDO::class)
                ->defineConstructor()
                    ->defineParameter('dsn')
                        ->defineDependency(new StringReference('mysql:host=localhost;port=3306;dbname=stylelike.io;charset=UTF8'))
                    ->end()
                    ->defineParameter('username')
                        ->defineDependency(new StringReference('samsonos'))
                    ->end()
                    ->defineParameter('passwd')
                        ->defineDependency(new StringReference('AzUzrcVe4LJJre9f'))
                    ->end()
                    ->defineParameter('options')
                        ->defineDependency((new CollectionReference())
                            ->addItem(new CollectionItem(
                                new ConstantReference('\PDO::ATTR_ERRMODE'),
                                new ParameterReference('pdo_exception')
                            ))
                            ->addItem(new CollectionItem(
                                new ConstantReference('\PDO::ATTR_DEFAULT_FETCH_MODE'),
                                new ConstantReference('\PDO::FETCH_ASSOC')
                            ))
                        )
                    ->end()
                ->end()
            ->end()
            ->addDefinition(XmlProductClass::class)
                ->defineConstructor()
                    ->defineParameter('shoes')
                        ->defineDependency(new ClassReference(Shoes::class))
                    ->end()
                    ->defineParameter('val')
                        ->defineDependency(new StringReference('value'))
                    ->end()
                    ->defineParameter('val1')
                        ->defineDependency(new StringReference('value1'))
                    ->end()
                    ->defineParameter('arr')
                        ->defineDependency(new CollectionReference(['param' => 'value']))
                    ->end()
                ->end()
                ->defineMethod('setLeg')
                    ->defineParameter('driver')
                        ->defineDependency(new ClassReference(SlowDriver::class))
                    ->end()
                ->end()
                ->defineProperty('driver')
                    ->defineDependency(new ClassReference(SlowDriver::class))
                ->end()
            ->end();

        $definitionBuilder = new DefinitionBuilder(new ParameterBuilder());
        $resolver = new XmlResolver();
        $resolver->resolve($definitionBuilder, $this->getMainXml());

        static::assertCount(2, $definitionBuilder->getDefinitionCollection());
        static::assertCount(1, $definitionBuilder->getParameterCollection());
    }

    public function getMainXml()
    {
        $code =<<<'PHP'
<?xml version="1.0" encoding="utf-8"?>
<container>
    <parameters>
        <param1 type="string" value="valu1"/>
    </parameters>
    <dependencies>
        <definition class="\PDO">
            <constructor>
                <arguments>
                    <dsn type="string" value="mysql:host=localhost;port=3306;dbname=stylelike.io;charset=UTF8"/>
                    <username type="string" value="samsonos"/>
                    <passwd type="string" value="AzUzrcVe4LJJre9f"/>
                    <options type="collection">
                        <item>
                            <key type="constant" value="\PDO::ATTR_ERRMODE"/>
                            <value type="parameter" value="pdo_exception"/>
                        </item>
                        <item>
                            <key type="constant" value="\PDO::ATTR_DEFAULT_FETCH_MODE"/>
                            <value type="constant" value="\PDO::FETCH_ASSOC"/>
                        </item>
                    </options>
                </arguments>
            </constructor>
        </definition>
        <definition class="samsonframework\container\tests\classes\annotation\XmlProductClass">
            <constructor>
                <arguments>
                    <shoes type="class" value="samsonframework\container\tests\classes\Shoes"/>
                    <val type="string" value="value"/>
                    <val1 type="string" value="value1"/>
                    <arr type="collection">
                        <item>
                            <key type="string" value="param"/>
                            <value type="string" value="value"/>
                        </item>
                    </arr>
                </arguments>
            </constructor>
            <methods>
                <setLeg>
                    <arguments>
                        <driver type="class" value="samsonframework\container\tests\classes\SlowDriver"/>
                    </arguments>
                </setLeg>
            </methods>
            <properties>
                <driver type="class" value="samsonframework\container\tests\classes\SlowDriver"/>
            </properties>
        </definition>
    </dependencies>
</container>
PHP;
        return $code;
    }
}
