<?php

require '../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

switch ($request->getPathInfo()) {
    case '/':
        $response->setContent('This is the website home');
        break;

    case '/about':
        $response->setContent('This is the about page');
        break;

    default:
        $response->setContent(sprintf('%s is not found on this server.', $request->getPathInfo()));
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
}

$response->send();
