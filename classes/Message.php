<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 04.02.19
 * Time: 11:39
 */

include_once '../conf/config.php';
header("Content-Type: text/html; charset=utf-8");

class Message

{
    public $message_id;
    public $subject;
    public $body;
    public $datetime;

   static function getMessages(){

        try{
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("
            Select message_id, subject, body, datetime from dlrg.messages order by datetime desc limit 5");
            $stmt->execute();
            $row = $stmt->rowCount();

            if($row > 0){

                $messageList = array();
                while ($row = $stmt->fetch()) {

                    $message = new Message();
                    $message->message_id = $row['message_id'];
                    $message->subject = utf8_encode($row['subject']);
                    $message->body = utf8_encode($row['body']);
                    $message->datetime = $row['datetime'];

                    $messageList[] = $message;

                    $body = $row['body'];

                }
                return $messageList;

            }else{

                return $myObj->message = "No Messages";
            }
        }catch (PDOException $e){
            return $myObj->message = $e;
        }




    }


    function groupUpdate ($id, $token){


        try{
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $checkRow = $dbh->prepare("Select group_id from dlrg.group WHERE group_id = $id ");
            $checkRow->execute();
            $row = $checkRow->rowCount();

            if($row > 0){
                $stmt = $dbh->prepare("Update dlrg.group set token = '$token' WHERE group_id = $id");
                $stmt->execute();
                $myObj->message = "Update successful";
            }else{

                $myObj->message = "Wrong Group";
            }
        }catch (PDOException $e){
            $myObj->message = $e;
        }
        return $myObj;




    }



}