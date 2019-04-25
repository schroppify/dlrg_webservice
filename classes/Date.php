<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 04.02.19
 * Time: 11:39
 */

include_once '../conf/config.php';
header("Content-Type: text/html; charset=utf-8");

class Date

{
    public $event_id;
    public $title;
    public $startDate;
    public $startTime;
    public $endDate;
    public $endTime;
    public $organizer;
    public $category;
    public $location;

   static function getDates(){

        try{
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("
            Select date_id, startDate, startTime, endDate, endTime, organizer, category, location, title from dlrg.dates  ");
            $stmt->execute();
            $row = $stmt->rowCount();

            if($row > 0){

                $dateList = array();
                while ($row = $stmt->fetch()) {

                    $date = new Date();
                    $date->event_id = $row['date_id'];
                    $date->title = utf8_encode($row['title']);

                    $startDate = $row['startDate'];
                    $startDate = new DateTime($startDate);
                    $startDate = $startDate->format('d.m.Y');

                    $date->startDate = $startDate;
                    $date->startTime = $row['startTime'];

                    $endDate = $row['endDate'];
                    $endDate = new DateTime($endDate);
                    $endDate = $endDate->format('d.m.Y');

                    $date->endDate = $endDate;
                    $date->endTime = $row['endTime'];
                    $date->organizer = utf8_encode($row['organizer']);
                    $date->category = utf8_encode($row['category']);
                    $date->location = utf8_encode($row['location']);

                    $dateList[] = $date;


                }
                return $dateList;

            }else{

                return $myObj->message = "No Dates";
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