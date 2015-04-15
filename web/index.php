<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use Dice\Dice;
use Dice\Rule;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Framework\Core\Core;
use Framework\Event\RequestEvent;

$app = new Core();

$app->setEnvironment('dev');
$app->setApplicationRoot(dirname(dirname(__FILE__)));

$app->init();

$dice = new Dice();
$rule = new Rule();
$rule->shared = true;
$rule->constructParams = [include $app->getApplicationRoot() . '/app/config/config.php'];
$dice->addRule('Zend\Config\Config', $rule);


$app->map('/', function () use ($dice) {
    $config = $dice->create('Zend\Config\Config');
    return new Response('This is the home page of ' . $config->webroot);
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
