<?php

if (extension_loaded('redis')) {
    $redis = new Redis();
    $redis->connect('localhost');
    $redis->incr('holidayapi:requests:alltime');
} else {
    $redis = false;
}

require '../lib/HolidayAPIv1.php';
$api = new \HolidayAPI\v1($redis);

header('Content-Type: application/json; charset=utf-8');

$flags = JSON_UNESCAPED_UNICODE;

if (isset($_REQUEST['pretty'])) {
    $flags |= JSON_PRETTY_PRINT;
}

echo json_encode($api->getHolidays(), $flags);

if ($redis) {
    $redis->close();
}

