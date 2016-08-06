<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 2:20.
 */

namespace samsonframework\di;

use samsonframework\di\resolver\Resolver;
use samsonframework\psr\Request;
use samsonframework\psr\Response;

class DiMiddleware
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Resolver
     */
    protected $containerResolver;

    public function __construct(Resolver $resolver)
    {
        $this->containerResolver = $resolver;
    }

    public function handle(Request $request, Response $response, callable $next)
    {
        $applicationContext = new ApplicationContext($this->containerResolver);
        $applicationContext->createContainer();
        $this->container = $applicationContext->getContainer();

        return $response;
    }

    public function getContianer()
    {
        return $this->container;
    }
}
