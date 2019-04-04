<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 03.02.19
 * Time: 10:30
 */

include_once 'conf/config.php';
include_once 'classes/Operation.php';

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
            case "auth":
                header('HTTP/1.0 200 OK');



                echo json_encode($operations);
                break;


        }
    }else if($requestMethod == "POST") {
        $view = "";
        if(isset($_GET["post"]))
            $post = $_GET["post"];







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







