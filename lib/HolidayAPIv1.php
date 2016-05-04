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
            if (!isset($_REQUEST['pais']) || trim($_REQUEST['pais']) == '') {
                throw new \Exception('Se requiere el parametro pais.');
            } elseif (!isset($_REQUEST['ano']) || trim($_REQUEST['ano']) == '') {
                throw new \Exception('Se require el parametro año.');
            }

            $year     = $_REQUEST['ano'];
            $month    = isset($_REQUEST['mes'])   ? str_pad($_REQUEST['mes'], 2, '0', STR_PAD_LEFT) : '';
            $day      = isset($_REQUEST['dia'])     ? str_pad($_REQUEST['dia'],   2, '0', STR_PAD_LEFT) : '';
            $country  = isset($_REQUEST['pais']) ? strtoupper($_REQUEST['pais'])                  : '';
            $date     = $year . '-' . $month . '-' . $day;

            if ($month && $day) {
                if (strtotime($date) === false) {
                    throw new \Exception('La fecha dada (' . $date . ') es invalida.');
                }
            }

            $country_holidays = $this->calculateHolidays($country, $year);
        } catch (\Exception $e) {
            $payload['status'] = 400;
            $payload['error']  = $e->getMessage();
        }

        $status_message = $payload['status'] . ' ' . ($payload['status'] == 200 ? 'OK' : 'Bad Request');

        foreach (['HTTP/1.1', 'Status:'] as $header) {
            header($header . ' ' . $status_message, true, $payload['status']);
        }

        if ($payload['status'] == 200) {
            $payload['feriados'] = [];

            if ($month && $day) {
             if (isset($country_holidays[$year][$date])) {
                    $payload['feriados'] = $country_holidays[$year][$date];
                }
            } elseif ($month) {
                foreach ($country_holidays[$year] as $date => $country_holiday) {
                    if (substr($date, 0, 7) == $year . '-' . $month) {
                        $payload['feriados'] = array_merge($payload['feriados'], $country_holiday);
                    }
                }
            } else {
                $payload['feriados'] = $country_holidays[$year];
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
                //Ubica el archivo json que contiene los feriados de cada pais. Ej. BO.json.
                $country_file = '../data/' . $country . '.json';

                if (!file_exists($country_file)) {
                    throw new \Exception('El pais provisto (' . $country . ') no esta incluido al momento');
                }
                //obtiene feriados de dicho pais    
                $country_holidays    = json_decode(file_get_contents($country_file), true);
                $calculated_holidays = [];

                foreach ($country_holidays as $country_holiday) {
                    if (strstr($country_holiday['rule'], '%Y')) {
                        $rule = str_replace('%Y', $year, $country_holiday['rule']);
                    } 
                    //EASTER es pascuas. Pascuas es una fecha referencial para calcular feriados de carnaval y semana santa
                    elseif (strstr($country_holiday['rule'], '%EASTER')) {
                        $rule = str_replace('%EASTER', date('Y-m-d', strtotime($year . '-03-21 +' . easter_days($year) . ' days')), $country_holiday['rule']);
                    } else {
                        $rule = $country_holiday['rule'] . ' ' . $year;
                    }

                    if ($rule) {
                        $calculated_date = date('Y-m-d', strtotime($rule));
                        //se obtiene el nombre literal del dia
                        $day_name = date('l', strtotime($calculated_date));
                        //compar si tal dia es domingo
                        if($day_name == "Sunday"){
                            //si es domingo se recorre un dia en el calendario, que viene a ser lunes.
                            $calculated_date = date('Y-m-d', strtotime($calculated_date . ' +1 day'));
                        }

                        if (!isset($calculated_holidays[$calculated_date])) {
                            $calculated_holidays[$calculated_date] = [];
                        }
                        //se elige que campos mostrar en la respuesta json.    
                        $calculated_holidays[$calculated_date][] = [
                            'fecha'    => $calculated_date,
                            'nombre'    => $country_holiday['name']
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

}

