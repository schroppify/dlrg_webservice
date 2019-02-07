<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 03.02.19
 * Time: 10:30
 */

include_once 'config.php';
include_once 'classes/Operation.php';

header('HTTP/1.0 200 OK');
header('Content-Type: application/json');





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
    $operation = new Operation();

    if($requestMethod == 'GET'){
        $get = "";
        if(isset($_GET["get"]))
            $get = $_GET["get"];
        switch($get) {
            case "all":
                // to handle REST Url /mobile/list/
                $operations = $operation->getAllOperations();
                //$operations["alerting_group_name"] = utf8_encode($operations["alerting_group_name"]);
                echo json_encode($operations);
                break;

            case "single":
                $myObj = $operation->getOperationDetails($_GET['id']);
                if($myObj == null){
                    header('HTTP/1.0 404 NOT FOUND');
                    $myObj->errorMessage = "The Operation was not found";
                }else{
                    header('HTTP/1.0 200 OK');
                }
                $myObj->location = utf8_encode($myObj->location);
                $myObj->alerting_group = utf8_encode($myObj->alerting_group);
                $myObj->city = utf8_encode($myObj->city);
                echo json_encode($myObj);
                break;
            case "test";
                $myObj->user = $_SERVER['PHP_AUTH_USER'];
                $myObj->pw = $_SERVER['PHP_AUTH_PW'];
                echo json_encode($myObj);
        }
    }else if($requestMethod == "PUT") {
        $view = "";
        if(isset($_GET["put"]))
            $put = $_GET["put"];
        switch($put) {
            case "group":
                header('HTTP/1.0 201 CREATED');
                echo json_encode($operation->putGroupInOperation(1, 1));
                break;

            case "people":
                header('HTTP/1.0 201 CREATED');
                echo json_encode($operation->putPeopleInOperation(1, 1));
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







