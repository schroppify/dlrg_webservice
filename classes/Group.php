<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 04.02.19
 * Time: 11:39
 */

include_once '../conf/config.php';
header("Content-Type: text/html; charset=utf-8");
class Group
{
    function getPeople($id){

        try{
            $dbh = new PDO($GLOBALS['dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("
            Select prename, lastname, people_id from dlrg.people WHERE group_id = $id ");
            $stmt->execute();
            $row = $stmt->rowCount();

            if($row > 0){

                while ($row = $stmt->fetch()) {

                        $myObj->prename = $row['prename'];
                        $myObj->lastname = $row['lastname'];



            $stmt2 = $dbh->prepare("
            SELECT 
             qualification.name,
             qualification.number
              
            From 
              peopleToQualification
            INNER JOIN qualification on peopleToQualification.qualification_id = qualification.qualification_id
            where 
            peopleToQualification.people_id=:id");

                $stmt2->bindParam(':id', $row['people_id']);
                $stmt2->execute();

                while ($row2 = $stmt2->fetch()) {
                    $object[] = array(
                        "name" => $row2['name'],
                        "number" => $row2['number']
                    );
                    $qualifications[]= $object;
                    $object = null;
                }
                $myObj->qualifications = $qualifications;




                    $peopleList[] = $myObj;


                }
                return $peopleList;

            }else{

                $myObj->message = "No People";
            }
        }catch (PDOException $e){
            $myObj->message = $e;
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