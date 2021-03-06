<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 03.02.19
 * Time: 10:30
 */

include_once 'conf/config.php';
include_once 'classes/User.php';

if(!isset($_SERVER['PHP_AUTH_USER']) and !isset($_SERVER['PHP_AUTH_PW'])){
    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
    header('HTTP/1.0 401 Unauthorized');
    $status = array('error' => 1, 'message' => 'Access denied 401!');
    echo json_encode($status);
    exit;
}

if(checkAuth()){
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $dbConnection = $connectionMessage;

    $myObj->dbConnection = $dbConnection;

    if($requestMethod == 'GET'){
        $get = "";
        if(isset($_GET["get"]))
            $get = $_GET["get"];
        switch($get) {
            case "auth":
                header('HTTP/1.0 200 OK');



                $status = array('error' => 0, 'message' => 'OK');
                echo json_encode($status);

                break;

            case "qualifications":
                header('HTTP/1.0 200 OK');


               $userData = User::getQualifications($_GET['id']);


                echo json_encode($userData);
                break;
            case "retrainings":
                header('HTTP/1.0 200 OK');


                $userData = User::getRetrainings($_GET['id']);


                echo json_encode($userData);
                break;
        }
    }else if($requestMethod == "POST") {
        $view = "";
        if(isset($_GET["post"]))
            $post = $_GET["post"];


        switch($post) {
            case "qualification":
                header('HTTP/1.0 201 CREATED');
                $input = json_decode(file_get_contents('php://input'), true);

                $myObj->qualification_id = $input["qualification_id"];
                $myObj->people_id = $input["people_id"];
                $myObj->exam_date = $input["exam_date"];
                $myObj->exam_number = $input["exam_number"];
                $myObj->exam_location = $input["exam_location"];
                $myObj->examiner = $input["examiner"];
                $myObj->examiner_number= $input["examiner_number"];


                echo json_encode(User::postQualification($myObj));
                break;

        }

    }else{
        header('HTTP/1.0 403 FORBIDDEN');
    }
}else{
    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
    header('HTTP/1.0 401 Unauthorized');
    $status = array('error' => 2, 'message' => 'Wrong User and/or Password!');
    echo json_encode($status);
}




function checkAuth(){
    if($_SERVER['PHP_AUTH_USER' ] == 'Testuser' and $_SERVER['PHP_AUTH_PW'] == 'Testpassword'){
        return true;
    }else{
        return false;
    }

}







