<?php
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

switch ($request_uri[0]) {
    case '/':
        require '../views/home.php';
        break;

    case '/v1/holidays':
        require '../views/api.php';
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        break;
}

