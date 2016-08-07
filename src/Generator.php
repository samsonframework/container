<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 22:21.
 */

namespace samsonframework\di;

use samsonframework\di\metadata\ClassMetadata;
use samsonframework\di\scope\ScopeManager;
use samsonphp\generator\Generator as ClassGenerator;

class Generator
{
    const FUNCTION_NAME = 'get';
    const CACHE_CLASS_NAME = 'CacheContainer';
    const CONTAINER_ALIAS = 'container';

    /**
     * @var string
     */
    public static $cacheFile = '../src/../cache/CacheContainer.php';

    /**
     * @var ClassGenerator
     */
    protected $generator;

    /**
     * Generator constructor.
     */
    public function __construct()
    {
        $this->generator = new ClassGenerator();
    }

    /**
     * Init generator.
     */
    public function init()
    {
        $this->preGenerate();

        if (file_exists(self::$cacheFile)) {
            unlink(self::$cacheFile);
        }
    }

    /**
     * Create class definition.
     */
    protected function preGenerate()
    {
        $this->generator->defClass(self::CACHE_CLASS_NAME, Container::class);
        $this->generator->text('public')->defFunction(self::FUNCTION_NAME, ['$name']);
    }

    /**
     * Add new service to container generator.
     *
     * @param ClassMetadata $metadata
     */
    public function addService(ClassMetadata $metadata)
    {
        $names = array_merge([$metadata->name, $metadata->identifier], $metadata->aliases);
        $ifCondition = '$name === \''.$metadata->className.'\'';
        foreach ($names as $name) {
            $ifCondition .= ' || $name === \''.$name.'\'';
        }

        $this->generator->defIfCondition($ifCondition);
        $this->generator->newLine("\t".'return new '.$metadata->className.'(');
        $i = 0;
        foreach ($metadata->dependencies as $argName => $service) {
            $separator = (++$i) < count($metadata->dependencies) ? ',' : '';
            $this->generator->newLine("\t\t".'$this->'.self::FUNCTION_NAME.'(\''.$service['service'].'\')'.$separator);
        }
        $this->generator->newLine("\t".');');
        $this->generator->endIfCondition();
    }

    /**
     * Create container and get it.
     *
     * @param ScopeManager $scopeManager
     *
     * @return mixed
     */
    public function createContainer(ScopeManager $scopeManager)
    {
        $this->postGenerate();

        $this->generator->write(self::$cacheFile);

        require_once self::$cacheFile;
        $className = self::CACHE_CLASS_NAME;

        return new $className($scopeManager);
    }

    /**
     * Add finish definition.
     */
    protected function postGenerate()
    {
        // Add container service
        $this->generator->defIfCondition('$name === \'' . self::CONTAINER_ALIAS . '\'');
        $this->generator->newLine("\t" . 'return $this;');
        $this->generator->endIfCondition();

        $this->generator->newLine("throw new \Exception(sprintf('Service with name %s not found', \$name));");
        $this->generator->endFunction();
        $this->generator->endClass();
    }
}
