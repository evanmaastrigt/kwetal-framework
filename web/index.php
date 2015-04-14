<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Framework\Event\RequestEvent;
use Framework\Core\Core;


$app = new Core();

$app->map('/', function () {
    return new Response('This is the home page');
});

$app->map('/about', function () {
    return new Response('This is the about page');
});

$app->map('/hello/{name}', function ($name) {
    return new Response('Hello '.$name);
});

$app->on('request', function (RequestEvent $event) {
    if ('/admin' == $event->getRequest()->getPathInfo()) {
        echo 'Access Denied!';
        exit;
    }
});

$request = Request::createFromGlobals();

$response = $app->handle($request);

$response->send();


