<?php
include_once 'classes/Message.php';
include_once '../conf/config.php';


    $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare("
            Select message_id, subject, message, datetime from dlrg.message order by datetime limit 5 ");
    echo $dbh;
    $stmt->execute();
    $row = $stmt->rowCount();
    if($row > 0){

        while ($row = $stmt->fetch()) {

            $myObj->message_id = $row['message_id'];
            $myObj->subject = $row['subject'];
            $myObj->message = $row['message'];
            $myObj->datetime = $row['datetime'];

            $messageList[] = $myObj;

        }
        //return $messageList;
        //print_r($messageList);

    }else{

        $myObj->message = "No Messages";
    }


echo $myObj;