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
		.error{
			color: red;
		}
	</style>
</head>
<body>

<?php
	$session_time = 1800;//seconds
	

	function verifyUser(){
		if(!isset($_POST["password"])){
			return;
		}
		$db = new mysqli('18.139.34.10:3306','tony','amakau123','users_tes');
		if($db === false){
			renderError($db -> error);
			exit();
		}
		$key = "";
		if(isset($_POST["user_name"])){
			$key = $key."username='".$_POST["user_name"] ;
		}elseif (isset($_POST["email"])){
			$key = $key."email='".$_POST["email"] ;
		}else{
			renderError("Email and UserName not given");
			$db -> close();
			return;
		}
		$res = $db -> query("SELECT id from users WHERE ".$key."' AND password='".$_POST["password"]."';");
		if ($res){
			if ($res-> num_rows === 1){
				$_SESSION["userID"] = $res -> fetch_array()['id'];
				$_SESSION["created"] = time();
				time_nanosleep(5, 1);
				header('Location: http://localhost/tutorials/Auth-Form-Template/dashboard.php');
			}else{
				renderError("Wrong email/user_name or password ".$res-> num_rows);
			}
		}else{
			renderError($db -> error);
		}
		$db -> close();
		return;
	}

	function renderError($err){
		echo '<p class="error">ERROR: '.$err.'</p>';
	}

	session_start();
	if(isset($_SESSION['created']) && isset($_SESSION['userID'])){
		if(time() - $_SESSION['created'] < $session_time){
			header('Location: http://localhost/tutorials/Auth-Form-Template/dashboard.php');
		}
	}
	verifyUser();

?>
<section>
	<h1> Login  </h1>
	<form method="POST" action="">
		<p> If you have not register for an account, please <a href="/tutorials/Auth-Form-Template/register.php">go to registration page </a></p>

		<div>
			<label for="email"> Email </label>
			<input type="email" name="email">
		<div>

		<div>
			<label for="user_name"> User Name </label>
			<input required="true" type="text" name="user_name">
		<div>

		<div>
			<label for="password"> Password </label>
			<input required="true" type="password" name="password">
		<div>
	
		<button> Login </button>
	</form>
</section>
</body>
</html>