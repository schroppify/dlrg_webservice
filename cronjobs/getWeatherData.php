<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 09.02.19
 * Time: 10:48
 */
date_default_timezone_set('Europe/Berlin');
$data = new stdClass();


getLocation();

function getLocation(){
    try{

        $dbh = new PDO('mysql:host=localhost;dbname=dlrg', 'dlrg_breisgau', 'Atel37*5');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $dbh->prepare("Select latitude, longitude from location");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            getWeatherData($row['latitude'],$row['longitude']);
            sleep(1);
        }

    }catch (PDOException $e){
        $myObj->message = $e;
        echo $myObj->message;
    }
}



function getWeatherData($lat, $lon){
    $apiKey = '59e296075f1351500b18e23f03f63ed9';

    try {
        $url = 'https://api.openweathermap.org/data/2.5/weather?appid=' . $apiKey . '&lat=' . $lat . '&lon=' . $lon.'&units=metric';
        //echo $url;

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        $data = json_decode(utf8_encode($data));
        $tempC = $data->main->temp;
        $windSpeed = $data->wind->speed;
        if(isset($data->wind->deg)){
            $windDirection = $data->wind->deg;
        }else{
            $windDirection = 'k.A.';
        }

        $weatherId = $data->weather[0]->id;
        $weatherIcon = $data->weather[0]->icon;

        $weatherStation = $data->name;
        $updateTime = date('Y-m-d H:i:s');
        try{

            $dbh = new PDO('mysql:host=localhost;dbname=dlrg', 'dlrg_breisgau', 'Atel37*5');

            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dbh->prepare("
            Update 
              location 
            set 
              temp = '$tempC',
              windSpeed = '$windSpeed',
              windDirection = '$windDirection',
              weather_id = '$weatherId',
              weatherStation = '$weatherStation',
              lastUpdate = '$updateTime',
              weatherIcon = '$weatherIcon'
            WHERE 
              latitude like $lat 
            AND 
              longitude like $lon
            ");
            $stmt->execute();

        }catch (PDOException $e){
            $myObj->message = $e;
            echo $myObj->message;
        }


    }catch(Exception $ex) {

        /*  Temperatur
        *   Windstärke
         *  Windrichtung
         *  Niederschlagsart
         * Niederschlagsbeschreibung
         * Messstation
         */
        echo $ex->getMessage();
    }
}

