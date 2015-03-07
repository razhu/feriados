<?php
namespace HolidayAPI;

class v1
{
    private $cache = false;

    public function __construct($cache = false)
    {
        $this->cache = $cache;
    }

    public function getHolidays()
    {
        if (ini_get('date.timezone') == '') {
            date_default_timezone_set('UTC');
        }

        $payload  = ['status' => 200];
        $holidays = [];

        try {
            if ($_SERVER['REQUEST_METHOD'] != 'GET') {
                throw new \Exception('This API only supports GET requests.');
            } elseif (!isset($_GET['country']) || trim($_GET['country']) == '') {
                throw new \Exception('The country parameter is required.');
            } elseif (!isset($_GET['year']) || trim($_GET['year']) == '') {
                throw new \Exception('The year parameter is required.');
            }

            $year     = $_GET['year'];
            $month    = isset($_GET['month'])   ? str_pad($_GET['month'], 2, '0', STR_PAD_LEFT) : '';
            $day      = isset($_GET['day'])     ? str_pad($_GET['day'],   2, '0', STR_PAD_LEFT) : '';
            $country  = isset($_GET['country']) ? strtoupper($_GET['country'])                  : '';
            $date     = $year . '-' . $month . '-' . $day;

            if ($month && $day) {
                if (strtotime($date) === false) {
                    throw new \Exception('The supplied date (' . $date . ') is invalid.');
                }
            }

            if ($this->cache) {
                $cache_key        = 'holidayapi:' . $country . ':holidays:' . $year;
                $country_holidays = $this->cache->get($cache_key);
            } else {
                $country_holidays = false;
            }

            if ($country_holidays) {
                $country_holidays = unserialize($country_holidays);
            } else {
                $country_file = '../data/' . $country . '.json';

                if (!file_exists($country_file)) {
                    throw new \Exception('The supplied country (' . $country . ') is not supported at this time.');
                }

                $country_holidays    = json_decode(file_get_contents($country_file), true);
                $calculated_holidays = [];

                foreach ($country_holidays as $country_holiday) {
                    if (strstr($country_holiday['rule'], '%Y')) {
                        $rule = str_replace('%Y', $year, $country_holiday['rule']);
                    } elseif (strstr($country_holiday['rule'], '%EASTER')) {
                        $rule = str_replace('%EASTER', date('Y-m-d', strtotime($year . '-03-21 +' . easter_days($year) . ' days')), $country_holiday['rule']);
                    } else {
                        $rule = $country_holiday['rule'] . ' ' . $year;
                    }

                    $calculated_date = date('Y-m-d', strtotime($rule));

                    if (!isset($calculated_holidays[$calculated_date])) {
                        $calculated_holidays[$calculated_date] = [];
                    }

                    $calculated_holidays[$calculated_date][] = [
                        'name'    => $country_holiday['name'],
                        'country' => $country,
                        'date'    => $calculated_date,
                    ];
                }

                $country_holidays = $calculated_holidays;

                if ($this->cache) {
                    $this->cache->set($cache_key, serialize($country_holidays), 3600);
                }
            }
        } catch (\Exception $e) {
            $payload['status'] = 400;
            $payload['error']  = $e->getMessage();
        }

        $status_message = $payload['status'] . ' ' . ($payload['status'] == 200 ? 'OK' : 'Bad Request');

        foreach (['HTTP/1.1', 'Status:'] as $header) {
            header($header . ' ' . $status_message, true, $payload['status']);
        }

        if ($payload['status'] == 200) {
            $payload['holidays'] = [];

            if ($month && $day) {
                if (isset($country_holidays[$date])) {
                    $payload['holidays'] = $country_holidays[$date];
                }
            } elseif ($month) {
                foreach ($country_holidays as $date => $country_holiday) {
                    if (substr($date, 0, 7) == $year . '-' . $month) {
                        $payload['holidays'][] = $country_holiday;
                    }
                }
            } else {
                $payload['holidays'] = $country_holidays;
            }
        }

        return $payload;
    }
}

