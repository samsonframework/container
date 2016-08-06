<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.07.2016
 * Time: 0:16.
 */

namespace samsonframework\di;

use samsonframework\di\scope\Scope;
use samsonframework\di\metadata\ClassMetadata;
use samsonframework\di\resolver\Resolver;
use samsonframework\di\scope\ControllerScope;
use samsonframework\di\scope\ScopeManager;
use samsonframework\di\util\ClassIterator;

class ApplicationContext
{
    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @var ClassIterator
     */
    protected $classIterator;

    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * @var array
     */
    protected $scopeList;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ScopeManager
     */
    protected $scopeManager;

    public function __construct(Resolver $resolver)
    {
        $this->generator = new Generator();
        $this->classIterator = new ClassIterator();
        $this->resolver = $resolver;
        $this->scopeManager = new ScopeManager();

        $this->scopeManager->add(new ControllerScope());
    }

    /**
     * Create container.
     */
    public function createContainer()
    {
        $this->generator->init();
        foreach ($this->classIterator->getIterator() as $className => $reflectionClass) {
            /** @var ClassMetadata $metadata */
            $metadata = $this->resolver->resolve($reflectionClass);
            if ($metadata->scopes) {
                foreach ($metadata->scopes as $scope) {
                    if ($this->scopeManager->has($scope)) {
                        $this->scopeManager->get($scope)->add($metadata);
                    }
                }
            }
            $this->generator->addService($metadata);
        }
        $this->container = $this->generator->createContainer($this->scopeManager);
    }

    /**
     * Build collection.
     */
    public function buildScopes()
    {
        $list = $this->scopeManager->getList();
        /** @var Scope $scope */
        foreach ($list as $scope) {
            $this->scopeManager->get($scope->getName())->build($this->container);
        }
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
