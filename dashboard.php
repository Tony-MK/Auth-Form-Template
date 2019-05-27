<!DOCTYPE html>
<html>
<head>
	<?php
	session_start();
	if(!isset($_SESSION['created'])){
		header('Location: login.php');
	}
	if(!isset($_SESSION['userID'])){
		header('Location: login.php');
	}
	$session_time = 1800;//seconds
	if(time() - $_SESSION['created'] >= $session_time){
		header('Location: login.php');
	}
	?>

<title>Dashboard </title>
</head>
<body>
<section>
	<div>
		<?php

		$db = new mysqli('18.139.34.10:3306','tony','','users_tes');
		if($db === false){
			echo $db -> error;
			exit();
		}

		if ($res = $db -> query("SELECT id,first_name,last_name,email,username,birthday,gender FROM users WHERE id=".$_SESSION['userID'].";")){
			$n = $res -> num_rows;
			if ($n === 1){
				$arr = $res -> fetch_array();
				echo "<p> Id :".$arr['id']."</p>";
				echo "<p> First Name :".$arr['first_name']."</p>";
				echo "<p> Last Name :".$arr['last_name']."</p>";
				echo "<p> Email :".$arr['email']."</p>";
				echo "<p> UserName :".$arr['username']."</p>";
				$d = strtotime($arr['birthday']);
				echo "<p> BirthDay :".date("l, jS ",$d)." of ".date(" M , Y",$d)."</p>";
				echo "<p> Gender :".($arr['gender'] === "0" ? "Male":"Female")."</p>";

			}else if ($n === 0){
				echo "No Rows found";
			}else{
				echo "Mulitple Rows Founds";
			}
			exit();
		}
		echo $db -> error; 
		$db -> close();
		exit();
	?>

	</div>

</section>
	

</body>
</html>