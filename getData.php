<?php

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}
if ($action == "get") { // return events by date
    $data = json_decode(file_get_contents('data.json'),TRUE);

    // collapse the events by days
    $dat = array();
    foreach($data as $key => $value) {
        if (!isset($dat[$key])) {
            $dat[$key] = array();
        }
        foreach($value as $val) {
            $day = new DateTime($val);
            $day = $day->format('Y-m-d');
            if (!isset($dat[$key][$day])) {
                $dat[$key][$day] = 0;
            }
            $dat[$key][$day] = $dat[$key][$day] + 1;
        }
    }
    
    echo(json_encode($dat));
} elseif ($action == "tick") {
    $what = "";
    if (isset($_GET['what'])) {
        $what = $_GET['what'];
    } else {
        echo("What is not set");
        return; // do nothing
    }
    if ($what == "") {
        echo("What is empty");
        return; // do nothing
    }
    $when = date(DATE_ATOM);
    if (isset($_GET['when'])) {
        $when = $_GET['when'];
    }
    if (!file_exists('data.json')) {
        file_put_contents('data.json', '{}');
    }
    // someone else could write the file right now... we should make the overwrite atomic
    $data = json_decode(file_get_contents('data.json'),TRUE);

    if (!isset($data[$what])) {
        $data[$what] = array();
    }
    $data[$what][] = $when;

    // ok how to we make this atomic? Create a temp file first, move the file afterwards
    $temp = tempnam('/tmp', 'tick-tock');
    file_put_contents($temp, json_encode($data));
    rename($temp, 'data.json');
    // file_put_contents('data.json', json_encode($data));    
}

?>