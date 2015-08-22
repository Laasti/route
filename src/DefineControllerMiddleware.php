<?php

namespace Laasti\Route;

use Laasti\Stack\MiddlewareInterface;
use League\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines the request controller using the RouteCollection dispatcher.
 * 
 * Use ControllerDefinitionStrategy as the RouteCollection dispatcher.
 * By default, _controllerDefinition will be define in the request attributes.
 *
 */
class DefineControllerMiddleware implements MiddlewareInterface
{

    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * @var string
     */
    protected $requestParameter;

    /**
     * Constructor
     * 
     * @param RouteCollection $routes
     * @param string $requestParameter Name used as the request attribute
     */
    public function __construct(RouteCollection $routes, $requestParameter = '_controllerDefinition')
    {
        $this->routes = $routes;
        $this->requestParameter = $requestParameter;
    }

    /**
     * Middleware method, calls the RouteCollection dispatcher to define the controller
     *
     * @param Request $request
     * @return Request
     */
    public function handle(Request $request)
    {
        $definition = $this->routes->getDispatcher()->dispatch($request->getMethod(), $request->getPathInfo());

        $request->attributes->set($this->requestParameter, $definition);
        $request->attributes->add($definition->getArguments());

        return $request;
    }

}
