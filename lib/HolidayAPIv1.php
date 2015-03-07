<?php
namespace HolidayAPI;

class v1
{
    private $cache = false;

    public function __construct($cache = false)
    {
        if ($_SERVER['SERVER_NAME'] != '127.0.0.1') {
            $this->cache = $cache;
        }
    }

    public function getHolidays()
    {
        if (ini_get('date.timezone') == '') {
            date_default_timezone_set('UTC');
        }

        $payload  = ['status' => 200];
        $holidays = [];

        try {
            if (!isset($_REQUEST['country']) || trim($_REQUEST['country']) == '') {
                throw new \Exception('The country parameter is required.');
            } elseif (!isset($_REQUEST['year']) || trim($_REQUEST['year']) == '') {
                throw new \Exception('The year parameter is required.');
            }

            $year     = $_REQUEST['year'];
            $month    = isset($_REQUEST['month'])   ? str_pad($_REQUEST['month'], 2, '0', STR_PAD_LEFT) : '';
            $day      = isset($_REQUEST['day'])     ? str_pad($_REQUEST['day'],   2, '0', STR_PAD_LEFT) : '';
            $country  = isset($_REQUEST['country']) ? strtoupper($_REQUEST['country'])                  : '';
            $previous = isset($_REQUEST['previous']);
            $upcoming = isset($_REQUEST['upcoming']);
            $date     = $year . '-' . $month . '-' . $day;

            if ($previous && $upcoming) {
                throw new \Exception('You cannot request both previous and upcoming holidays.');
            } elseif (($previous || $upcoming) && (!$month || !$day)) {
                $request = $previous ? 'previous' : 'upcoming';
                $missing = !$month   ? 'month'    : 'day';

                throw new \Exception('The ' . $missing . ' parameter is required when requesting ' . $request . ' holidays.');
            }

            if ($month && $day) {
                if (strtotime($date) === false) {
                    throw new \Exception('The supplied date (' . $date . ') is invalid.');
                }
            }

            $country_holidays = $this->calculateHolidays($country, $year, $previous || $upcoming);
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
                if ($previous) {
                    $country_holidays = $this->flatten($date, $country_holidays[$year - 1], $country_holidays[$year]);
                    prev($country_holidays);
                    $payload['holidays'] = current($country_holidays);
                } elseif ($upcoming) {
                    $country_holidays = $this->flatten($date, $country_holidays[$year], $country_holidays[$year + 1]);
                    next($country_holidays);
                    $payload['holidays'] = current($country_holidays);
                } elseif (isset($country_holidays[$year][$date])) {
                    $payload['holidays'] = $country_holidays[$year][$date];
                }
            } elseif ($month) {
                foreach ($country_holidays[$year] as $date => $country_holiday) {
                    if (substr($date, 0, 7) == $year . '-' . $month) {
                        $payload['holidays'] = array_merge($payload['holidays'], $country_holiday);
                    }
                }
            } else {
                $payload['holidays'] = $country_holidays[$year];
            }
        }

        return $payload;
    }

    private function calculateHolidays($country, $year, $range = false)
    {
        $return = [];

        if ($range) {
            $years = [$year - 1, $year, $year + 1];
        } else {
            $years = [$year];
        }

        foreach ($years as $year) {
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
                    } elseif (in_array($country, ['BR', 'US']) && strstr($country_holiday['rule'], '%ELECTION')) {
                        switch ($country) {
                            case 'BR':
                                $years = range(2014, $year, 2);
                                break;
                            case 'US':
                                $years = range(1788, $year, 4);
                                break;
                        }

                        if (in_array($year, $years)) {
                            $rule = str_replace('%ELECTION', $year, $country_holiday['rule']);
                        } else {
                            $rule = false;
                        }
                    } else {
                        $rule = $country_holiday['rule'] . ' ' . $year;
                    }

                    if ($rule) {
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
                }

                $country_holidays = $calculated_holidays;

                ksort($country_holidays);

                foreach ($country_holidays as $date_key => $date_holidays) {
                    usort($date_holidays, function($a, $b)
                    {
                        $a = $a['name'];
                        $b = $b['name'];

                        if ($a == $b) {
                            return 0;
                        }

                        return $a < $b ? -1 : 1;
                    });

                    $country_holidays[$date_key] = $date_holidays;
                }

                if ($this->cache) {
                    $this->cache->setex($cache_key, 3600, serialize($country_holidays));
                }
            }

            $return[$year] = $country_holidays;
        }

        return $return;
    }

    private function flatten($date, $array1, $array2)
    {
        $holidays = array_merge($array1, $array2);

        // Injects the current date as a placeholder
        if (!isset($holidays[$date])) {
            $holidays[$date] = false;
            ksort($holidays);
        }

        // Sets the internal pointer to today
        while (key($holidays) !== $date) {
            next($holidays);
        }

        return $holidays;
    }
}

