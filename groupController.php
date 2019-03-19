<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 03.02.19
 * Time: 10:30
 */

include_once 'conf/config.php';
include_once 'classes/Group.php';

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
    $group = new Group();

    if($requestMethod == 'GET'){
        $get = "";
        if(isset($_GET["get"]))
            $get = $_GET["get"];
        switch($get) {
            case "people":
                header('HTTP/1.0 200 OK');

                echo json_encode($group->getPeople($_GET['id']));

                break;

            case "single":
                break;
        }

    }else if($requestMethod == "POST") {
        $view = "";
        if(isset($_GET["post"]))


            $post = $_GET["post"];
        switch($post) {
            case "group":
                $id = $_GET['id'];
                header('HTTP/1.0 201 CREATED');
                $input = json_decode(file_get_contents('php://input'), true);
                $token = $input["token"];

                echo json_encode($group->groupUpdate($id, $token));
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







