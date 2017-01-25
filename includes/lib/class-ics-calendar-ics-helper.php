<?php

class ICS_Calendar_ICS_Helper
{

    private $temp_file;

    public function __construct()
    {
        $this->temp_file = dirname(dirname(__DIR__)) . '/temp';
    }


    private function download_ics()
    {
        $ics_file = get_option('ics_calendar_ics_url');
        $ics_timeout = get_option('ics_calendar_ics_timeout');
        $filetime = filemtime($this->temp_file . '/calendar.ics');
        $now = time();
        if (($filetime + ($ics_timeout * 60)) < $now) {
            $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch, CURLOPT_URL, $ics_file);
            $result = curl_exec($ch);
            file_put_contents($this->temp_file . '/calendar.ics', $result);
        }
    }

    public function get_ics()
    {
        $this->download_ics();
        $ical = new ical($this->temp_file . '/calendar.ics');
        $events = $ical->events();
        if (has_action('ics_calendar_render')) {
            do_action('ics_calendar_render', $events);
        } else {
            foreach ($events as $index => $event) {
                $startDate = new DateTime($event['DTSTART']);
                if ($startDate->format('U') < time()) {
                    continue;
                }
                echo '<b>'.$event['SUMMARY'].'</b><br/>';
                $endDate = new DateTime($event['DTEND']);
                echo date_i18n('d. F Y',$startDate->format('U'));
                echo ' '.$startDate->format('H:i');
                echo ' - ';
                echo date_i18n('d. F Y',$endDate->format('U'));
                echo ' '.$endDate->format('H:i');
                echo '<br/>';

                echo stripcslashes($event['LOCATION']) ;
                echo '<br/>';
                echo '<br/>';
            }
        }


    }

}