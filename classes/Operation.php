<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 04.02.19
 * Time: 11:39
 */

include_once '../conf/config.php';
header("Content-Type: text/html; charset=utf-8");
class Operation
{


    function getOperationDetails($operation_id){
        $this->location = utf8_encode($this->location);

        try {
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("
            SELECT 
              operation.alerting_time, 
              location.name , 
              operation.alerting_group_id,
              operation.message, 
              city.name AS city_name,
              alertingGroup.name AS alerting_group_name,
              location.location_id,
              location.latitude,
              location.longitude
            From operation
            INNER JOIN location ON location.location_id = operation.location_id
            INNER JOIN city ON city.city_id = location.city_id
            INNER JOIN alertingGroup ON alertingGroup.alerting_group_id = operation.alerting_group_id
            
            where operation.operation_id=:id");
            $stmt->bindParam(':id', $operation_id);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $myObj->alerting_time = $row['alerting_time'];
                $myObj->location = $row['name'];
                $myObj->city = $row['city_name'];
                $myObj->alerting_group = $row['alerting_group_name'];
                $myObj->message = $row['message'];
                $myObj->lat = $row['latitude'];
                $myObj->long = $row['longitude'];

                $stmt2 = $dbh->prepare("
            SELECT 
              measuringPoint.measuring_point_id,
              measuringPoint.name,
              measuringPoint.km,
              measuringPoint.timestamp,
              measuringPoint.value,
              measuringPoint.trend,
              measuringPoint.mw
            From 
              measuringPointToLocation
            INNER JOIN measuringPoint ON measuringPoint.measuring_point_id = measuringPointToLocation.measuring_point_id
            where 
              measuringPointToLocation.location_id=:id");
                $stmt2->bindParam(':id', $row['location_id']);
                $stmt2->execute();
                while ($row2 = $stmt2->fetch()) {
                    $object[] = array(
                        "id" => $row2['measuring_point_id'],
                        "name" => $row2['name'],
                        "km" => $row2['km'],
                        "timestamp" => $row2['timestamp'],
                        "value" => $row2['value'],
                        "trend" => $row2['trend'],
                        "mw" => $row2['mw']
                    );
                    $measuringPoints[]= $object;
                    $object = null;
                }
                $myObj->measuring_point = $measuringPoints;

            }
            return $myObj;
        } catch (PDOException $e) {
            $myObj->errorMessage = $e->getMessage();
            return $myObj;
        }
}

    function getAllOperations(){
        try {
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("
            SELECT 
              operation.operation_id,
              operation.alerting_time,
              alertingGroup.name AS alerting_group_name,
              city.name AS city_name,
              location.name AS location_name,
              operation.end_time
              
            From operation
            INNER JOIN alertingGroup ON alertingGroup.alerting_group_id = operation.alerting_group_id
            INNER JOIN location ON location.location_id = operation.location_id
            INNER JOIN city ON city.city_id = location.city_id  
            WHERE operation.end_time LIKE '00:00:00'          ");
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $operation = array(
                    "operation_id" => $row['operation_id'],
                    "alerting_time" => $row['alerting_time'],
                    "alerting_group_name" => utf8_encode($row['alerting_group_name']),
                    "city_name" => utf8_encode($row['city_name']),
                    "location_name" => utf8_encode($row['location_name'])
                );
                $operations[] = $operation;
            }

            return $operations;
        } catch (PDOException $e) {
            $myObj->errorMessage = $e->getMessage();
            return $myObj;
        }

    }
    function putGroupInOperation($group_id, $operation_id, $status){

        try{
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $checkRow = $dbh->prepare("Select * from groupsInOperation WHERE group_id = $group_id and operation_id = $operation_id");
            $checkRow->execute();
            $row = $checkRow->rowCount();

            if($row > 0){
                $stmt = $dbh->prepare("Update groupsInOperation set status = '$status' WHERE group_id = $group_id and operation_id = $operation_id");
                $stmt->execute();
                $myObj->message = "Update successful";
            }else{
                $stmt = $dbh->prepare("Insert into groupsInOperation (group_id, operation_id, status) VALUES ($group_id, $operation_id, '$status')");
                $stmt->execute();
                $myObj->message = "Insert successful";
            }
        }catch (PDOException $e){
            $myObj->message = $e;
        }
        return $myObj;
    }

    function putPeopleInOperation($operation_id, $people_id, $status){
        try{
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $checkRow = $dbh->prepare("Select * from peopleInOperation WHERE people_id = $people_id and operation_id = $operation_id");
            $checkRow->execute();
            $row = $checkRow->rowCount();

            if($row > 0){
                $stmt = $dbh->prepare("Update peopleInOperation set status = '$status' WHERE people_id = $people_id and operation_id = $operation_id");
                $stmt->execute();
                $myObj->message = "Update successful";
            }else{
                $stmt = $dbh->prepare("Insert into peopleInOperation (people_id, operation_id, status) VALUES ($people_id, $operation_id, '$status')");
                $stmt->execute();
                $myObj->message = "Insert successful";
            }
        }catch (PDOException $e){
            $myObj->message = $e;
        }
        return $myObj;
    }

}