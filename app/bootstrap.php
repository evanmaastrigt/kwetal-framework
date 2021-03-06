<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require realpath(__DIR__ . '/../vendor/autoload.php');

use Framework\Core\Core;
use Framework\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;

$app = new Core();

$app->setEnvironment('dev');
$app->setApplicationRoot(dirname(dirname(__FILE__)));

$app->init();

$container = $app->getContainer();


$app->map('/', function () use ($container) {
    $config = $container->create('Zend\Config\Config');
    return new Response('This is the home page of ' . $config->db_user);
});

$app->map('/about', function () {
    return new Response('This is the about page');
});

$app->map('/hello/{name}', function ($name) {
    return new Response('Hello ' . $name);
});

$app->on('request', function (RequestEvent $event) {
    if ('/admin' == $event->getRequest()->getPathInfo()) {
        echo 'Access Denied!';
        exit;
    }
});

return $app;
