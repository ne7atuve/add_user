<?php 

function sql_connection()
{
	$pdo = new PDO("mysql:host=array;dbname=my_project;", "root", "");
	return $pdo;
}

$pdo = sql_connection();


function get_user_by_email($email, $pdo)
{
	$sql = "SELECT * FROM users WHERE email=:email";
	$statement = $pdo->prepare($sql);
	$statement->execute(["email" => $email]);
	$user = $statement->fetch(PDO::FETCH_ASSOC);
	return $user;
}




function add_user($email, $password, $pdo)
{

	$sql = "INSERT INTO users (email, password, role, name, work, telephone, address, vk, telegram, instagram, status, image) VALUES (:email, :password, :role, '', '', '', '', '', '', '', '', '')";
	$passwd = password_hash($password, PASSWORD_DEFAULT);
	$statement = $pdo->prepare($sql);
	$id = $statement->execute(["email" => $email, "password" => $passwd, "role" => "user"]);
	if ($id) 
	{ 
		return $pdo->lastInsertId();
	} 
	else 
	{
		return false; 
	}
}


function set_flash_message($name, $message)
{
	$_SESSION[$name] = $message;
}

function redirect_to($path)
{
	header("Location: {$path}");
	exit;
}

function display_flash_message($name)
{
	if(isset($_SESSION[$name]))
	{
		echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\"> {$_SESSION[$name]}</div>";
		unset($_SESSION[$name]);
	}
}

function login($email, $password, $pdo)
{
	$user = get_user_by_email($email, $pdo);

	if($user)
	{
		if(password_verify($password, $user["password"]) === true)
		{
			$_SESSION["login"] = true;
			$_SESSION["user"] = $user;
			$_SESSION["current_user"] = $email;
			$_SESSION["role"] = $user["role"];
			$_SESSION["id"] = $user["id"];
			redirect_to("/Учебный проект/users.php");
		}
		else
		{
			set_flash_message("danger", "Неправильно введен пароль!");
			redirect_to("/Учебный проект/page_login.php");
		}
	}
	else
	{
		set_flash_message("danger", "Такого пользователя не существует!");
		redirect_to("/Учебный проект/page_login.php");
	}
	return true;
}

function add_info($id, $name, $work, $telephone, $address, $pdo)
{

	$sql = "UPDATE users SET name = :name, work = :work, telephone = :telephone, address = :address WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(["name" => $name, "work" => $work, "telephone" => $telephone, "address" => $address, "id" => $id]);
}

function add_social_networks($id, $vk, $telegram, $instagram, $pdo)
{

	$sql = "UPDATE users SET vk = :vk, telegram = :telegram, instagram = :instagram WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(["vk" => $vk, "telegram" => $telegram, "instagram" => $instagram, "id" => $id]);
}

function set_status($id, $status, $pdo) 
{

	$sql = "UPDATE users SET status = :status WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$final = $statement->execute(["status" => $status, "id" => $id]);

}

function add_image($id, $image, $pdo)
{


	$image = uniqid("image_", true) . ".jpg";
	$tmp_name = $_FILES["image"]["tmp_name"];
	move_uploaded_file($tmp_name, "images/" . $image);

	$sql = "UPDATE users SET image = :image WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$result = $statement->execute(["image" => $image, "id" => $id]);

}


function get_user_by_id($id, $pdo)
{
	$sql = "SELECT * FROM users WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(["id" => $id]);
	return $statement->fetch(PDO::FETCH_ASSOC);
}



function check_Id()
{
	if(!$_SESSION["user"] and $_SESSION["login"] !== true)
	{
		return false;
	}
	if($_SESSION["role"] == "admin")
		{
			$id = $_GET["id"];
		}
		else
		{
			$id = $_SESSION["id"];
		}
		return $id;
}


?>