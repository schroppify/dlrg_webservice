<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 04.02.19
 * Time: 10:09
 */

$servername = "localhost";
$username = "dlrg_breisgau";
$password = "Atel37*5";

$GLOBALS['dsn'] = 'mysql:host=localhost;dbname=dlrg';
$GLOBALS['db_user'] = 'dlrg_breisgau';
$GLOBALS['db_password'] = 'Atel37*5';

try {
    $conn = new PDO("mysql:host=$servername;dbname=dlrg;charset=utf8", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connectionMessage = "Connected successfully";
}
catch(PDOException $e)
{
    $connectionMessage = "Connection failed: " . $e->getMessage();
}