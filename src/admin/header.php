<?php
session_start();
include '../main.php';

if ((isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > EXP_TIME)) || $_SESSION['admin_login'] == false) {
	header("Location: ".MAIN_URL."admin/logout");
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$result = $conn->query("SELECT * FROM sgh_admins WHERE id=" . $_SESSION['admin_id'] . " LIMIT 1");
$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo 'پنل کاربری | ' . $title; ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.5 -->
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/bootstrap/css/bootstrap-rtl.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/plugins/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/bootstrap/css/ionicons.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/plugins/select2/select2.min.css">
	<!-- Data Tables -->
	<link rel="stylesheet" type="text/css" href="<?php echo MAIN_URL; ?>theme/plugins/DataTables/datatables.min.css" />
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/dist/css/AdminLTE-rtl.css">
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/dist/css/skins/skin-blue-rtl.css">
	<!-- Datepicker -->
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/plugins/datepicker/datepicker3-rtl.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/plugins/iCheck/flat/blue.css">
	<!-- Custom style -->
	<link rel="stylesheet" href="<?php echo MAIN_URL; ?>theme/style.css">
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini sgh-admin-panel">
	<div class="wrapper">

		<!-- Main Header -->
		<header class="main-header">

			<!-- Logo -->
			<a data-pjax href="<?php echo MAIN_URL; ?>admin/" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"><b>پـ</b>نل</span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg"><b>پنل</b>کاربری</span>
			</a>

			<!-- Header Navbar -->
			<nav class="navbar navbar-static-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<!-- Navbar left Menu -->
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!-- User Account Menu -->
						<li class="dropdown user user-menu">
							<!-- Menu Toggle Button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<!-- The user image in the navbar-->
								<img src="<?php if (empty($admin['pic'])) : echo MAIN_URL . 'theme/dist/img/noavatar.png';
											else : echo MAIN_URL.$admin['pic'];
											endif; ?>" class="user-image" alt="User Image">
								<!-- hidden-xs hides the username on small devices so only the image appears. -->
								<span class="hidden-xs"><?php echo $admin['username']; ?></span>
							</a>
							<ul class="dropdown-menu">
								<!-- The user image in the menu -->
								<li class="user-header">
									<img src="<?php if (empty($admin['pic'])) : echo MAIN_URL . 'theme/dist/img/noavatar.png';
												else : echo MAIN_URL.$admin['pic'];
												endif; ?>" class="img-circle" alt="User Image">
									<p>
										<?php echo $admin['username']; ?>
										<small><?php echo $admin['firstname'] . ' ' . $admin['lastname']; ?></small>
									</p>
								</li>
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-right">
										<a data-pjax href="<?php echo MAIN_URL; ?>admin/pass-change" class="btn btn-default btn-flat">تغییر رمز عبور</a>
									</div>
									<div class="pull-left">
										<a href="<?php echo MAIN_URL; ?>admin/logout" class="btn btn-default btn-flat">خروج</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>