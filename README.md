# ICS Calendar
A Wordpress Plugin to easily insert a ICS calendar file from another server and keep it in sync.

Download the plugin here: https://github.com/nahakiole/ics-calendar/archive/master.zip

Then install it by uploading it in your Wordpress installation.

## Configuration

The Settings can be found under Settings, ICS Calendar. 
Put in the URL to your ICS file and how long the file should be cached until it will get refetched.

To show the output on your page you can either use the widget to include your calendar or use the shortcode ```[ics_calendar]```

If you want to customize the output you can use the hook ```ics_calendar_render``` which gets passed a array with the list of events.

This is a bad example of how you could potentially implement this function. ;D

```php
function calender_render($events){
    $output = "";
    $max = 5;
    foreach ($events as $index => $event) {
        if ($max == 0) {
            break;
        }
        $max--;
        $startDate = new DateTime($event['DTSTART']);
        if ($startDate->format('U') < time()) {
            continue;
        }
        $endDate = new DateTime($event['DTEND']);
        $output .= 'Treffen am '.date_i18n('d. F Y',$startDate->format('U'));
        $output .= '<br/>';
        $geo = explode(';',$event['GEO']);
        $output .= '<a target="_blank" href="https://www.google.ch/maps/place/'.urlencode($geo[0]).'+'.urlencode($geo[1]).'">'.
            preg_replace('/\([^)]*\)/','',str_replace(
                [ '/','\n', '\;',],
                ['<br/>',' ', '<br/>', ],
                nl2br($event['LOCATION']))) .'</a>';
        $output .= '<br>';
        $output .= $startDate->format('H:i');
        $output .= ' bis ';
        $output .= $endDate->format('H:i');
        $output .= '<br>';
        $output .= '<a target="_blank" href="' . $event['URL'] . '">Weitere Informationen</a>';
        $output .= '<br>';
        $output .= '<br>';
    }
    return $output;
}

add_filter( 'ics_calendar_render', 'calender_render');
```

## License and credit

Used WordPress-Plugin-Template to initialize the plugin: https://github.com/hlashbrooke/WordPress-Plugin-Template (GPL)

Using ics-parser from Martin Thoma to parse the ics files: https://github.com/MartinThoma/ics-parser (MIT)