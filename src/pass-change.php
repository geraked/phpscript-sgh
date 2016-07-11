<?php
$title = 'رمز عبور';
include 'header.php';
include 'sidebar.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<div id="loading"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>

	<div data-pjax id="pjax-container">	

	<?php
	// define variables and set to empty values
	$passwordErr = $conf_passwordErr = $pcSuc = $pcErr = "";

	$password = $conf_password = "";
	
	if (isset($_POST["pcsubmit"])) {
		
		if (empty($_POST["password"])) {
			$passwordErr = "رمز عبور را وارد کنید";
		}
		else {
			$password = test_input($_POST["password"]);
		}
		
		if (empty($_POST["conf_password"])) {
			$conf_passwordErr = "رمز عبور را دوباره وارد کنید";
		}
		else {
			$conf_password = test_input($_POST["conf_password"]);
			if ($password != $conf_password) {
				$conf_passwordErr = "رمز عبور وارد شده مطابقت ندارد";
			}
		}
		
		if (empty($passwordErr) && empty($conf_passwordErr)) 
		{
			
			$sql = "UPDATE sgh_members SET password='".md5($password)."' WHERE id=".$user['id'];				
			
			if ($conn->query($sql) === TRUE) {
				$pcSuc = "رمز عبور شما با موفقیت تغییر یافت.";
			} 
			else {
				$pcErr = "مشکلی در تغییر رمز عبور به وجود آمده است.";
			}
		}
		
	}
	?>	
	
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $title; ?>
			<small>تغییر رمز عبور</small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<?php if(!empty($pcSuc)) : ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-check"></i>پیام!</h4>
			<?php echo $pcSuc; ?>
		</div>
		<?php elseif(!empty($pcErr)) : ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-ban"></i>پیام!</h4>
			<?php echo $pcErr; ?>
		</div>		
		<?php endif; ?>
		
		<!-- general form elements -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">فرم تغییر رمز عبور</h3>
			</div><!-- /.box-header -->
			
			<!-- form start -->
			<form data-pjax method="post" action="<?php echo MAIN_URL;?>pass-change" enctype="multipart/form-data" role="form">
				<div class="box-body">
					<div class="form-group <?php if(!empty($passwordErr)) : echo 'has-error'; endif; ?>">
						<label for="ampassword"><?php if(!empty($passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$passwordErr; else : echo "* رمز عبور جدید"; endif; ?></label>
						<input type="password" class="form-control" style="direction:ltr;" name="password" value="<?php echo $password; ?>" id="ampassword">
						<p class="help-block">استفاده از تمام کاراکترها مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($conf_passwordErr)) : echo 'has-error'; endif; ?>">
						<label for="amconf_password"><?php if(!empty($conf_passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$conf_passwordErr; else : echo "* تکرار رمز عبور"; endif; ?></label>
						<input type="password" class="form-control" style="direction:ltr;" name="conf_password" value="<?php echo $conf_password; ?>" id="amconf_password">
						<p class="help-block">رمز عبور را دوباره وارد نمایید.</p>
					</div>
				</div><!-- /.box-body -->

				<div class="box-footer">
					<input type="hidden" name="pcsubmit" value="1">
					<button type="submit" class="btn btn-primary">اعمال تغییرات</button>
				</div>
			</form>
		</div><!-- /.box -->
		
	</section><!-- /.content -->
	
	</div><!-- /#pjax-container -->

</div><!-- /.content-wrapper -->
<?php include 'footer.php'; ?>