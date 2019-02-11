<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 05.02.19
 * Time: 08:08
 */
include_once 'config.php';



$riverLevel = new RiverLevel();
$data = $riverLevel->getData();
echo json_encode($data);
