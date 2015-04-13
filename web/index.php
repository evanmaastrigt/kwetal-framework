<?php

switch($_SERVER['REQUEST_URI']) {
    case '/':
        echo 'This is the home page';
        break;
    case '/about':
        echo 'This is the about page';
        break;
    default:
        echo sprintf('%s is not found on this server.', $_SERVER['REQUEST_URI']);
}
