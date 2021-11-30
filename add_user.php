<?php
session_start();
require "func.php";

$name = $_POST["name"];
$work = $_POST["work"];
$telephone = $_POST["telephone"];
$address = $_POST["address"];
$email = $_POST["email"];
$password = $_POST["password"];
$vk = $_POST["vk"];
$telegram = $_POST["telegram"];
$instagram = $_POST["instagram"];
$status = $_POST["status"];
$image = $_FILES["image"];

$pdo = sql_connection();
$user = get_user_by_email($email, $pdo);

if(!empty($user))
{
	set_flash_message("danger", "Этот email уже занят другим пользователем");
	redirect_to("/Учебный проект/create_user.php");
}


$id = add_user($email, $password, $pdo); 

if ($id) 
{ 
	set_status($id, $status, $pdo);
	add_image($id, $image, $pdo);

	add_info($id, $name, $work, $telephone, $address, $pdo);
	add_social_networks($id, $vk, $telegram, $instagram, $pdo);

	set_flash_message("success", "Пользователь успешно добавлен!");
	redirect_to("/Учебный проект/users.php");

} 
else 
{
	set_flash_message ("danger", "Ошибка при добавлении нового пользователя");
}


?>