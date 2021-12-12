<?php
session_start();
require "func.php";
$pdo = sql_connection();

$id = $_GET["id"];

$name = $_POST["name"];
$work = $_POST["work"];
$telephone = $_POST["telephone"];
$address = $_POST["address"];


add_info($id, $name, $work, $telephone, $address, $pdo);

var_dump($id);





?>