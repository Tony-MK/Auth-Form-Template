<!DOCTYPE html>
<html>
<head>
	<title>Account Registration</title>
	<style type="text/css">
		form {
			display: block;
		}
		div {
			margin-top: 1vh;
		}
		.success{
			color:green;
		}
		.error{
			color: red;
		}
	</style>
</head>
<body>


	

<?php
	$session_time = 1800;//seconds
	$GLOBALS['db_name'] = "users_tes";
	$GLOBALS['db_addr'] = '18.139.34.10:3306';
	$GLOBALS['db_user'] = 'tony';
	$GLOBALS['db_pass'] = "";
	$GLOBALS['tb_name'] = 'users';

	$GLOBALS['STATEMENTS'] = array(
		'cT' =>
			'CREATE TABLE users(
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				first_name VARCHAR(50) NOT NULL,
				last_name VARCHAR(50) NOT NULL,
				username VARCHAR(50) NOT NULL UNIQUE,
				email VARCHAR(100) NOT NULL UNIQUE,
				password VARCHAR(100) NOT NULL,
				birthday DATE NOT NULL,
				gender BIT(1) NOT NULL,
				serect_word VARCHAR(50) NOT NULL UNIQUE;
			)',

		'cD' => 
			"CREATE DATABASE ".$GLOBALS['db_name'],

		'uD' =>
			"USE ".$GLOBALS['db_name'],

		'iU' =>
			"INSERT INTO ".$GLOBALS['tb_name']." (first_name,last_name,username,email,password,birthday,gender,serect_word) ",
	);

	
	function renderErr($err=''){
		echo '<h2 class="error"> A Error Has Ocurred </h2>';
		echo '<p class="error"> '.$err.'</p>';
	}


	function createDatabase($conn=''){
		if($conn-> query($GLOBALS['STATEMENTS']['cD']) === true){
			if($conn -> query($GLOBALS['STATEMENTS']['uD']) == true){
				return false;
			}
			renderErr("Selecting Database ".$conn -> error);
			return true;
		}
		renderErr("Execting Database Creation".$conn -> error);
		return  true;
	}

	function createTable($conn){
		if($conn -> query($GLOBALS['STATEMENTS']['cT']) == false){
			return $conn -> error;
		}
		return null;
	}	

	function insertUser($conn){
		$stmt = $GLOBALS["STATEMENTS"]["iU"]." values('".
			$conn->real_escape_string($_POST["first_name"])."','".
			$conn->real_escape_string($_POST["last_name"])."','".
			$conn->real_escape_string($_POST["user_name"])."','".
			$conn->real_escape_string($_POST["email"])."','".
			$conn->real_escape_string($_POST["password"])."','".
			$conn->real_escape_string($_POST["birth_date"])."','".
			$conn->real_escape_string($_POST['gender'])."','".
			$conn->real_escape_string($_POST["secret_word"])."')";

		if($conn -> query($stmt) === true){
			echo '<h2 class="success">Registration Successful </h2>';
			echo '<p class="success"> <a href="/tutorials/Auth-Form-Template/login.php">Login</a> to get Started </p>';
			$conn -> close();
			exit();
		}
		switch ($conn -> error[0]) {
			case 'U':
				$err = createTable($conn);
				if($err !== null ){
					renderErr($err);

				}
				insertUser($conn);
				break;
			case 'D':
				if(strpos($conn -> error,'email')){
					renderErr("Email is already registered. Try logining if it is yours. ",$conn-> error);
					return false;
				}elseif (strpos($conn -> error,'_')){
					renderErr("Secret word already choosen by a user");
					return false;
				}
				renderErr("Username already choosen by a user");
			
				break;
			default:
				renderErr($conn -> error);
				break;
		};
		return true;
	}

	
	

	session_start();
	// Checking if user has logined in;
	if(isset($_SESSION['created'])){
		if(time() - $_SESSION['created'] < $session_time){
			header('Location: http://localhost/tutorials/Auth-Form-Template/dashboard.php');
		}
		header('Location: http://localhost/tutorials/Auth-Form-Template/login.php');
	}else if(isset($_SESSION['userID'])){

		header('Location: http://localhost/tutorials/Auth-Form-Template/login.php');

	}else{	

		if(isset($_POST["first_name"])){

			//Connecting to the database 
			$conn = new mysqli($GLOBALS['db_addr'],$GLOBALS['db_user'],$GLOBALS['db_pass']);
			if($conn === false){
				renderErr("Connecting to Database",$conn -> connect_errno);
				exit();
			}

			//Selecting user's table
			if($conn -> query($GLOBALS['STATEMENTS']['uD']) == false){
				echo $conn -> error;
				if (createDatabase($conn) === true){
					$conn -> close();
					exit();
				}
			}
			// Inserting user's into the database
			insertUser($conn);
			$conn -> close();
		}
	}
	exit();
?>

<section>
	<h1> Account Registration </h1>
		<p> If you have an account please, <a href="/tutorials/Auth-Form-Template/login.php">go to login page </a></p>
	<form method="POST" action="register.php">
		<div>
			<label for="first_name"> First Name </label>
			<input required="true" type="text" name="first_name">

			<label for="last_name"> Last Name </label>
			<input required="true" type="text" name="last_name">
		<div>


		<div>
			<label for="email"> Email </label>
			<input required="true" type="email" name="email">
		<div>

		<div>
			<label for="user_name"> User Name </label>
			<input required="true" type="text" name="user_name">
		<div>

		<div>
			<label for="password"> Password </label>
			<input required="true" type="password" name="password">
		<div>

		
		
		<div>
			<label > Male </label>
			<input required="true" type="radio" name="gender" value="0">
			<label > Female </label>
			<input required="true" type="radio" checked="true" name="gender" value="1">
		<div>
		

		<div>
			<label for="birth_date"> Birth Date </label>
			<input required="true" type="date" name="birth_date">
		<div>

		<div>
			<label for="secret_word"> Sercet Word </label>
			<input required="true" type="text" name="secret_word">
		<div>
		

		<button> Register </button>
	</form>
</section>


</body>
</html>
