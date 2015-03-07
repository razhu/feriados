<?php
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

switch ($request_uri[0]) {
    case '/':
        require '../views/home.php';
        break;

    case '/v1/holidays':
        require '../lib/HolidayAPIv1.php';
        $api = new \HolidayAPI\v1();

        header('Content-Type: application/json; charset=utf-8');

        $flags = JSON_UNESCAPED_UNICODE;

        if (isset($_REQUEST['pretty'])) {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode($api->getHolidays(), $flags);
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        break;
}

