<?php
session_start();
include_once 'main.php';

// define variables and set to empty values
$Err = "";
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (empty($_POST["username"])) {
		$Err = "نام کاربری را وارد کنید!";
	} 
	else {
		$username = test_input($_POST["username"]);
	}
	
	if (empty($_POST["password"])) {
		$Err = "رمز عبور را وارد کنید!";
	} 
	else {
		$password = test_input($_POST["password"]);
	}

	if (!empty($username) && !empty($password)) {
		$result = $conn->query("SELECT * FROM sgh_members WHERE username='$username' AND password='".md5($password)."' LIMIT 1");
		if($result->num_rows == 1) {
			$row = $result->fetch_array();
			$_SESSION['user_login']	=	true;
			$_SESSION['user_id']	=	$row['id'];
			header("Location: ".MAIN_URL);
		}
		else {
			$Err = "نام کاربری یا رمز عبور اشتباه است!";
		}			
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=1,initial-scale=1,user-scalable=1" />
	<title>ورود اعضا</title>
	<!-- Custom CSS -->
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo MAIN_URL;?>theme/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo MAIN_URL;?>theme/bootstrap/css/bootstrap-rtl.css">
	<link rel="stylesheet" href="<?php echo MAIN_URL;?>theme/login.css">	
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	
	<section class="container">
	    <section class="login-form">
		<form method="post" action="<?php echo MAIN_URL;?>login" role="login">
			<h3><b>ورود</b> اعضا</h3>
			<div class="row">
				<div class="col-xs-12">
					<input type="text" name="username" value="<?php echo $username; ?>" placeholder="نام کاربری" class="form-control input-lg">
					<span class="glyphicon glyphicon-user"></span>
				</div>
				<div class="col-xs-12">
					<input type="password" name="password" value="<?php echo $password; ?>" placeholder="رمز عبور" class="form-control input-lg">
					<span class="glyphicon glyphicon-lock"></span>
				</div>
			</div>
			<button type="submit" name="submit" class="btn btn-lg btn-block btn-success">ورود</button>
			<section>
				<span class="err"><?php echo $Err; ?></span>
			</section>
		</form>
		</section>
	</section>

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo MAIN_URL;?>theme/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo MAIN_URL;?>theme/bootstrap/js/bootstrap.min.js"></script>	
</body>
</html>
<?php $conn->close(); ?>