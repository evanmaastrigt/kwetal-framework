<?php

namespace Framework\Core;

use Framework\Config\Configloader;
use Framework\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Core implements HttpKernelInterface
{
    /** @var RouteCollection */
    protected $routes = array();

    /** @var EventDispatcher */
    protected $dispatcher;

    /** @var  string */
    protected $applicationRoot;

    /** @var  string */
    protected $environment;


    public function __construct()
    {
        $this->routes = new RouteCollection();
        $this->dispatcher = new EventDispatcher();
    }

    public function init()
    {
        if (is_null($this->environment) || is_null($this->applicationRoot)) {
            throw new Exception\ConfigurationException('Some properties are not set in Core ("applicationRoot", "environment")');
        }

        $this->loadConfiration();


    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $event = new RequestEvent();
        $event->setRequest($request);
        $this->dispatcher->dispatch('request', $event);

        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $attributes = $matcher->match($request->getPathInfo());
            $controller = $attributes['controller'];
            unset($attributes['controller']);
            $response = call_user_func_array($controller, $attributes);
        } catch (ResourceNotFoundException $e) {
            $response = new Response(
                sprintf(
                    '%s is not found on this server.',
                    $request->getPathInfo()
                ),
                Response::HTTP_NOT_FOUND
            );
        }

        return $response;
    }

    public function map($path, $controller) {
        $this->routes->add(
            $path,
            new Route(
                $path,
                ['controller' => $controller]
            )
        );
    }

    public function on($event, $callback)
    {
        $this->dispatcher->addListener($event, $callback);
    }

    public function fire($event)
    {
        return $this->dispatcher->dispatch($event);
    }

    protected function loadConfiration()
    {
        $loader = new Configloader($this->getEnvironment(), $this->getApplicationRoot());
        $loader->load();
    }

    /**
     * @return string
     */
    public function getApplicationRoot()
    {
        return $this->applicationRoot;
    }

    /**
     * @param string $applicationRoot
     */
    public function setApplicationRoot($applicationRoot)
    {
        $this->applicationRoot = $applicationRoot;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
}
