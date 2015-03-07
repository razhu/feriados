<?php
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

switch ($request_uri[0]) {
    case '/':
        require '../views/home.php';
        break;

    case '/v1/holidays':
        require '../src/HolidayAPIv1.php';
        $api = new \HolidayAPI\v1();
        echo json_encode(
            $api->getHolidays(),
            isset($_REQUEST['pretty']) ? JSON_PRETTY_PRINT : false
        );
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        break;
}

