<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 05.02.19
 * Time: 08:08
 */
include_once 'config.php';
try {
    $dbh = new PDO($GLOBALS['dsn'], "dlrg", "dlrg");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare("SELECT operation.alerting_time, location.name , operation.alerting_group_id From operation
            LEFT JOIN location ON location.location_id = operation.location_id
            where operation.operation_id=1");
    //$stmt->bindParam(':id', '1');
    $stmt->execute();
    while ($row = $stmt->fetch()) {

        $myObj->alerting_group_id = $row['alerting_group_id'];
        $myObj->location= $row['name'];
        $myObj->alerting_time = $row['alerting_time'];

    }
    var_dump($myObj);
} catch (PDOException $e) {
    echo $e->getMessage();
}