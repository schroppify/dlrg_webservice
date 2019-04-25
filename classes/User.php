<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 04.02.19
 * Time: 11:39
 */

include_once '../conf/config.php';
header("Content-Type: text/html; charset=utf-8");
class User
{
    static function getQualifications($id){

        try{
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("
            SELECT 
             qualification.qualification_id,      
             qualification.name,
             qualification.number,
            peopleToQualification.exam_date
              
            From 
              peopleToQualification
            INNER JOIN qualification on peopleToQualification.qualification_id = qualification.qualification_id
            where 
            peopleToQualification.people_id=:id");

                $stmt->bindParam(':id', $id);
                $stmt->execute();

                while ($row = $stmt->fetch()) {


                    $myObj->qualification_id = $row['qualification_id'];
                    $myObj->name = $row['name'];
                    $myObj->number = $row['number'];
                    $exam_date = $row['exam_date'];
                    $exam_date = new DateTime($exam_date);
                    $exam_date = $exam_date->format('d.m.Y');
                    $myObj->examDate = $exam_date;




                    $qualifications[]= $myObj;
                    $myObj = null;
                }

                return $qualifications;

        }catch (PDOException $e){
            $myObj->message = $e;
        }
    }

    static function getRetrainings($id){
        try{
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("
            SELECT 
             
             retrainingToDo.retraining_id,
             retraining.name,
             retraining.validity,
             retraining.number
              
            From 
              retrainingToDo
            INNER JOIN retraining on retrainingToDo.retraining_id = retraining.retraining_id
            where 
            retrainingToDo.people_id=:id");

            $stmt->bindParam(':id', $id);
            $stmt->execute();

            while ($row = $stmt->fetch()) {


                $myObj->retraining_id = $row['retraining_id'];
                $myObj->name = utf8_encode($row['name']);
                $myObj->validity = $row['validity'];
                $myObj->number = $row['number'];




                $retrainings[]= $myObj;
                $myObj = null;
            }

            return $retrainings;

        }catch (PDOException $e){
            $myObj->message = $e;
            return $myObj;
        }
    }
    static function postQualification($qualification){
        try{
            $var = 'Test';
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $checkRow = $dbh->prepare("Select qualification_id from peopleToQualification WHERE people_id = $qualification->people_id and qualification_id = $qualification->qualification_id");
            $checkRow->execute();
            $row = $checkRow->rowCount();

            if($row > 0){

                $myObj->message = $qualification->examiner_number;
            }else{
                $stmt = $dbh->prepare("
                    Insert into peopleToQualification (
                        people_id, 
                        qualification_id,
                        exam_date,
                        examiner,
                        exam_number,
                        exam_location,
                        examiner_number
                                                       
                        ) 
                        VALUES (
                         $qualification->people_id, 
                         $qualification->qualification_id,
                         '$qualification->exam_date',
                         '$qualification->examiner',
                         '$qualification->exam_number',
                         '$qualification->exam_location',
                         '$qualification->examiner_number'
                         )");
                if($stmt->execute()){
                    $myObj->message = "Insert successful";

                }else{
                    $myObj->message = $qualification->exam_date;

                };
            }
        }catch (PDOException $e){
            $myObj->message = $e;
        }
        return $myObj;
    }






}