<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 08.02.19
 * Time: 11:34
 */




try{

    $dbh = new PDO('mysql:host=localhost;dbname=dlrg', 'dlrg_breisgau', 'Atel37*5');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare("Select name from measuringPoint");
    $stmt->execute();

    while ($row = $stmt->fetch()) {
    getData($row['name']);
    echo $row['name'];
    }

}catch (PDOException $e){
    $myObj->message = $e;
    echo $myObj->message;
}



function getData($name) {
    $data = new stdClass();
    $dataFiltered = new stdClass();

    try {
        $url = 'https://www.pegelonline.wsv.de/webservices/rest-api/v2/stations/'.$name.'.json?includeTimeseries=true&includeCurrentMeasurement=true';
       //echo $url;
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        $data = json_decode(utf8_encode($data));

        $dataFiltered->uuid = $data->uuid;
        $dataFiltered->name = $data->shortname;
        $dataFiltered->km = $data->km;
        $dataFiltered->value = $data->timeseries[0]->currentMeasurement->value;
        $dataFiltered->timestamp = $data->timeseries[0]->currentMeasurement->timestamp;
        $dataFiltered->trend = $data->timeseries[0]->currentMeasurement->trend;

        //var_dump($data);
        try{

            $dbh = new PDO('mysql:host=localhost;dbname=dlrg', 'dlrg_breisgau', 'Atel37*5');
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("Update measuringPoint
        set 
          TIMESTAMP = '$dataFiltered->timestamp',
          value = $dataFiltered->value,
          trend = $dataFiltered->trend
        WHERE 
          uuid = '$dataFiltered->uuid';
         ");
            $stmt->execute();
            $dataFiltered = null;

        }catch (PDOException $e){
            $myObj->message = $e;
            echo $myObj->message;
        }

    } catch(Exception $ex) {
         echo $ex->getMessage();
    }
}


