<?php
$title = 'مدیران';
include 'header.php';
include 'sidebar.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<div id="loading"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>

	<div data-pjax id="pjax-container">

	<?php if( (isset($_REQUEST['op']) && $_REQUEST['op']=='add') || (isset($_REQUEST['op']) && $_REQUEST['op']=='edit') ) : ?>	
	
	<?php
	// define variables and set to empty values
	$usernameErr = $passwordErr = $conf_passwordErr = $firstnameErr = $lastnameErr = $emailErr = $picErr = $picSuc = $aaSuc = $aaErr = "";
	
	if ($_REQUEST['op']=='add') {
		$id = $username = $password = $conf_password = $firstname = $lastname = $email = $pic = $_SESSION['aapic'] = "";
	}
	else {
		$result	= $conn->query("SELECT * FROM sgh_admins WHERE id=".test_input($_REQUEST['id'])." LIMIT 1");
		$row	= $result->fetch_assoc();
		extract($row);
		$_SESSION['aapic'] = $pic;
	}

	if (isset($_POST["aasubmit"])) {

		if (empty($_POST["username"])) {
			$usernameErr = "نام کاربری را وارد کنید";
		}
		else {
			$username = test_input($_POST["username"]);
			if (!preg_match("/^[a-z\d_-]{3,40}$/i",$username)) {
				$usernameErr = "نام کاربری وارد شده صحیح نیست";
			}
			else {
				$result = ($_REQUEST['op']=='edit') ? $conn->query("SELECT username, id FROM sgh_admins WHERE username='$username' AND id!=".test_input($_REQUEST['id'])." LIMIT 1") : $conn->query("SELECT username, id FROM sgh_admins WHERE username='$username' LIMIT 1");
				if($result->num_rows != 0) {
					$usernameErr = "این نام کاربری قبلا ثبت شده است";
				}						
			}					
		}
		
		if ($_REQUEST['op']=='add') {
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
		}
		
		if (!empty($_POST["firstname"])) {
			$firstname = test_input($_POST["firstname"]);
			if (!preg_match("@^[\s\x{0621}-\x{063A}\x{0640}-\x{0691}\x{0698}-\x{06D2}]+$@u",$firstname)) {
				$firstnameErr = "نام وارد شده صحیح نیست";
			}
		}

		if (!empty($_POST["lastname"])) {
			$lastname = test_input($_POST["lastname"]);
			if (!preg_match("@^[\s\x{0621}-\x{063A}\x{0640}-\x{0691}\x{0698}-\x{06D2}]+$@u",$lastname)) {
				$lastnameErr = "نام خانوادگی وارد شده صحیح نیست";
			}					
		}
		
		if (!empty($_POST["email"])) {
			$email = test_input($_POST["email"]);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "ایمیل وارد شده صحیح نیست";
			}					
		}

		if ( empty($usernameErr) && empty($passwordErr) && empty($conf_passwordErr) && empty($firstnameErr) &&
			empty($lastnameErr) && empty($emailErr) )		
		{

			if (isset($_FILES["pic"])) {

				$target_dir = "../uploads/avatars/admins/";
				$target_file = $target_dir . basename($_FILES["pic"]["name"]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);				
		
				if ( !empty(basename($_FILES["pic"]["name"])) && !empty($_SESSION['aapic']) ) {
					@unlink($_SESSION['aapic']);
					$_SESSION['aapic'] = "";
				}				
				
				if (!empty(basename($_FILES["pic"]["name"]))) {
					if ( empty($username) || !empty($usernameErr) ) {
						$picErr = "ابتدا نام کاربری را وارد نمایید";
						$uploadOk = 0;						
					}
					else {
						$check = getimagesize($_FILES["pic"]["tmp_name"]);
						if($check !== false) {
							//echo "فایل انتخاب شده یک تصویر است - " . $check["mime"] . ".";
							$uploadOk = 1;
							// Allow certain file formats
							if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
								$picErr = "تنها JPG ، JPEG و PNG مجاز است.";
								$uploadOk = 0;
							}
							else {
								// Check file size
								if ($_FILES["pic"]["size"] > 100000) {
									$picErr = "حجم تصویر بیشتر از حد مجاز است";
									$uploadOk = 0;
								}
								else {
									// Check if file already exists
									if (file_exists($target_file)) {
										$picErr = "این تصویر قبلا آپلود شده است.";
										$uploadOk = 0;
									}
									else {
										// Check if $uploadOk is set to 0 by an error
										if ($uploadOk == 0) {
											$picErr = "تصویر انتخاب شده آپلود نشد";
										// if everything is ok, try to upload file
										} 
										else {
											if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
												rename($target_file, $target_dir.$id.$username.'.'.$imageFileType);
												$target_file = $target_dir.$id.$username.'.'.$imageFileType;
												$_SESSION['aapic'] = str_replace('../', '', $target_file);
												$picSuc = "تصویر ". basename( $_FILES["pic"]["name"]). " با موفقیت آپلود گردید.";
											} 
											else {
												$picErr = "مشکلی در آپلود تصویر به وجود آمده است.";
											}
										}							
									}
								}
							}					
						}
						else {
							$picErr = "فایل انتخاب شده تصویر نیست.";
							$uploadOk = 0;
						}
					}	
				}	
	
			}

		}
		
		if ( empty($usernameErr) && empty($passwordErr) && empty($conf_passwordErr) && empty($firstnameErr) &&
			empty($lastnameErr) && empty($emailErr) && empty($picErr) ) 
		{
			$pic = $_SESSION['aapic'];
			
			if ($_REQUEST['op']=='add') {
				$sql = "INSERT INTO sgh_admins (username, password, firstname, lastname, email, pic) 
				VALUES ('$username', '".md5($password)."', '$firstname', '$lastname', '$email', '$pic')";
			}
			else {
				$sql = "UPDATE sgh_admins SET username='$username', firstname='$firstname', lastname='$lastname', email='$email', pic='$pic' WHERE id=".test_input($_REQUEST['id']);				
			}
			
			if ($conn->query($sql) === TRUE) {
				$aaSuc = ($_REQUEST['op']=='add') ? "مدیر ".$username." با موفقیت افزوده شد." : "مدیر ".$username." با موفقیت ویرایش گردید.";
				if ($_REQUEST['op']=='add') {
					$username = $password = $conf_password = $firstname = $lastname = $email = $pic = $_SESSION['aapic'] = $picSuc = "";
				}	
			} 
			else {
				$aaErr = ($_REQUEST['op']=='add') ? "مشکلی در ثبت مدیر ".$username." به وجود آمده است." : "مشکلی در ویرایش مدیر ".$username." به وجود آمده است.";
			}
		}
		
	}
	
	if (isset($_POST["delpic"])) {
		@unlink('../'.$_SESSION['aapic']);
		$_SESSION['aapic'] = "";
		
		$pic = $_SESSION['aapic'];
		
		if ($_REQUEST['op']=='edit') {
			$sql = "UPDATE sgh_admins SET pic='$pic' WHERE id=".test_input($_REQUEST['id']);
			if ($conn->query($sql) === TRUE) {
				$picSuc = "تصویر با موفقیت حذف گردید.";
			}
			else {
				$picErr = "مشکلی در حذف تصویر به وجود آمده است.";
			}
		}
	}	
	?>	
	
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $title; ?>
			<small><?php if($_REQUEST['op']=='add') : echo "افزودن مدیر جدید"; else : echo "ویرایش مدیران"; endif; ?></small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<?php if(!empty($aaSuc)) : ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-check"></i>پیام!</h4>
			<?php echo $aaSuc; ?>
		</div>
		<?php elseif(!empty($aaErr)) : ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-ban"></i>پیام!</h4>
			<?php echo $aaErr; ?>
		</div>		
		<?php endif; ?>
		
		<!-- general form elements -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php if($_REQUEST['op']=='add') : echo "فرم افزودن مدیر"; else : echo "فرم ویرایش مدیر"; endif; ?></h3>
			</div><!-- /.box-header -->
			
			<!-- form start -->
			<form data-pjax method="post" action="<?php if($_REQUEST['op']=='add') : echo MAIN_URL.'admin/admins?op=add'; else : echo MAIN_URL.'admin/admins?op=edit&id='.$_REQUEST['id']; endif; ?>" enctype="multipart/form-data" role="form">
				<div class="box-body">
					<div class="form-group <?php if(!empty($usernameErr)) : echo 'has-error'; endif; ?>">
						<label for="aausername"><?php if(!empty($usernameErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$usernameErr; else : echo "* نام کاربری"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="username" value="<?php echo $username; ?>" id="aausername">
						<p class="help-block">استفاده از ارقام، حروف لاتین، "_" و "-" مجاز می‌باشد. حداقل 3 کاراکتر.</p>
					</div>
					<?php if ($_REQUEST['op']=='add') : ?>
					<div class="form-group <?php if(!empty($passwordErr)) : echo 'has-error'; endif; ?>">
						<label for="aapassword"><?php if(!empty($passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$passwordErr; else : echo "* رمز عبور"; endif; ?></label>
						<input type="password" class="form-control" style="direction:ltr;" name="password" value="<?php echo $password; ?>" id="aapassword">
						<p class="help-block">استفاده از تمام کاراکترها مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($conf_passwordErr)) : echo 'has-error'; endif; ?>">
						<label for="aaconf_password"><?php if(!empty($conf_passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$conf_passwordErr; else : echo "* تکرار رمز عبور"; endif; ?></label>
						<input type="password" class="form-control" style="direction:ltr;" name="conf_password" value="<?php echo $conf_password; ?>" id="aaconf_password">
						<p class="help-block">رمز عبور را دوباره وارد نمایید.</p>
					</div>
					<?php endif; ?>
					<div class="form-group <?php if(!empty($firstnameErr)) : echo 'has-error'; endif; ?>">
						<label for="aafirstname"><?php if(!empty($firstnameErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$firstnameErr; else : echo "نام"; endif; ?></label>
						<input type="text" class="form-control" name="firstname" value="<?php echo $firstname; ?>" id="aafirstname">
						<p class="help-block">تنها کاراکترهای فارسی مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($lastnameErr)) : echo 'has-error'; endif; ?>">
						<label for="aalastname"><?php if(!empty($lastnameErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$lastnameErr; else : echo "نام خانوادگی"; endif; ?></label>
						<input type="text" class="form-control" name="lastname" value="<?php echo $lastname; ?>" id="aalastname">
						<p class="help-block">تنها کاراکترهای فارسی مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($emailErr)) : echo 'has-error'; endif; ?>">
						<label for="aaemail"><?php if(!empty($emailErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$emailErr; else : echo "ایمیل"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="email" value="<?php echo $email; ?>" id="aaemail">
						<p class="help-block">مثال: example@gmail.com</p>
					</div>
					<div class="form-group <?php if(!empty($picErr)) : echo 'has-error'; elseif(!empty($picSuc)) : echo 'has-success'; endif; ?>">
						<div class="btn btn-default btn-file">
							<i class="fa fa-paperclip"></i> تصویر پروفایل
							<input type="file" name="pic" id="aapic">
						</div>
						<p class="help-block"><?php if(!empty($picErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$picErr; elseif(!empty($picSuc)) : echo '<i class="fa fa-check"></i> '.$picSuc;  else : echo "حداکثر سایز مجاز 100 کیلوبایت می‌باشد."; endif; ?></p>
						<?php if(!empty($_SESSION['aapic'])) : ?>
							<div class="avatar-groupe">
								<img id="img-up" src="<?php echo MAIN_URL; ?><?php echo $_SESSION['aapic']; ?>" class="img-circle" width="100px" height="100px">
								<button class="btn btn-danger btn-xs img-del-btn" data-toggle="tooltip" data-placement="top" title="حذف" value=""><i class="fa fa-trash-o"></i></button>
							</div>
						<?php endif; ?>
					</div>
				</div><!-- /.box-body -->

				<div class="box-footer">
					<input type="hidden" name="aasubmit" value="1">
					<button type="submit" class="btn btn-primary"><?php if($_REQUEST['op']=='add') : echo "ثبت اطلاعات"; else : echo "ویرایش اطلاعات"; endif; ?></button>
				</div>
			</form>
		</div><!-- /.box -->
		
	</section><!-- /.content -->
	
	
	<?php else : ?>
	
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $title; ?>
			<small>لیست مدیران</small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<?php 
		$password = $conf_password = $passwordErr = $conf_passwordErr = $MChangePassSucc = $MChangePassErr = $deleteSuc = $deleteErr = "";
		
		if (isset($_POST["passChange"])) {
			if (empty($_POST["password"])) {
				$passwordErr = $MChangePassErr = "رمز عبور را وارد کنید";
			}
			else {
				$password = test_input($_POST["password"]);
			}
			
			if (empty($_POST["conf_password"])) {
				$conf_passwordErr = $MChangePassErr = "رمز عبور را دوباره وارد کنید";
			}
			else {
				$conf_password = test_input($_POST["conf_password"]);
				if ($password != $conf_password) {
					$conf_passwordErr = $MChangePassErr = "رمز عبور وارد شده مطابقت ندارد";
				}
			}			
			
			if (empty($passwordErr) && empty($conf_passwordErr)) {
				$sql = "UPDATE sgh_admins SET password='".md5($password)."' WHERE id=".test_input($_POST["id"]);
				if ($conn->query($sql) === TRUE) {
					$MChangePassSucc = "رمز عبور مدیر ".$_POST["username"]." با موفقیت تغییر یافت.";
					$password = $conf_password = "";
					
				} else {
					$MChangePassErr = "مشکلی در تغییر رمز عبور مدیر ".$_POST["username"]." به وجود آمده است.";
				}
			}		
		}		
		
		if (isset($_POST["delete"])) {
			$resultdel = $conn->query("SELECT * FROM sgh_admins WHERE id=".test_input($_POST["id"])." LIMIT 1");
			$rdel = $resultdel->fetch_assoc();
			@unlink('../'.$rdel['pic']);
			$sql = "DELETE FROM sgh_admins WHERE id=".test_input($_POST["id"]);
			if ($conn->query($sql) === TRUE) {
				$deleteSuc = "مدیر ".$_POST["username"]." با موفقیت حذف شد.";
			} else {
				$deleteErr = "مشکلی در حذف مدیر ".$_POST["username"]." به وجود آمده است.";
			}			
		}
		?>		
		
		<?php if(!empty($deleteSuc)) : ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-check"></i>پیام!</h4>
			<?php echo $deleteSuc; ?>
		</div>
		<?php elseif(!empty($deleteErr)) : ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-ban"></i>پیام!</h4>
			<?php echo $deleteErr; ?>
		</div>
		<?php elseif(!empty($MChangePassSucc)) : ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-check"></i>پیام!</h4>
			<?php echo $MChangePassSucc; ?>
		</div>
		<?php elseif(!empty($MChangePassErr)) : ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-ban"></i>پیام!</h4>
			<?php echo $MChangePassErr; ?>
		</div>
		<?php endif; ?>
		
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">لیست مدیران</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>نام کاربری</th>
							<th>نام</th>
							<th>نام خانوادگی</th>
							<th>ایمیل</th>
							<th>امکانات</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
						$result = $conn->query("SELECT * FROM sgh_admins");
						while($row = $result->fetch_assoc()) :
						?>
						
						<tr>
							<td><?php echo $row["username"]; ?></td>
							<td><?php echo $row["firstname"]; ?></td>
							<td><?php echo $row["lastname"]; ?></td>
							<td><?php echo $row["email"]; ?></td>
							<td>
								<a data-pjax href="<?php echo MAIN_URL;?>admin/admins?op=edit&id=<?php echo $row["id"]; ?>" class="btn btn-default btn-sm" role="button" data-toggle="tooltip" data-placement="top" title="ویرایش"><i class="fa fa-edit"></i></a>
								<span data-toggle="modal" data-target="#passChange<?php echo $row["id"]; ?>"><button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="تغییر رمز عبور"><i class="fa fa-key"></i></button></span>
								<span data-toggle="modal" data-target="#delete<?php echo $row["id"]; ?>"><button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="حذف"><i class="fa fa-trash-o"></i></button></span>
							</td>
						</tr>
						
						<div class="example-modal">
							<div class="modal fade" id="passChange<?php echo $row["id"]; ?>" tabindex="-1" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">تغییر رمز عبور مدیر <?php echo $row["username"]; ?></h4>
										</div>
										<form data-pjax method="post" action="<?php echo MAIN_URL.'admin/admins';?>" id="admin-pass<?php echo $row["id"]; ?>" role="form">
											<div class="modal-body">
												<div class="form-group <?php if(!empty($passwordErr)) : echo 'has-error'; endif; ?>">
													<label for="aapassword"><?php if(!empty($passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$passwordErr; else : echo "* رمز عبور"; endif; ?></label>
													<input type="password" class="form-control" style="direction:ltr;" name="password" value="<?php echo $password; ?>" id="aapassword" form="admin-pass<?php echo $row["id"]; ?>">
													<p class="help-block">استفاده از تمام کاراکترها مجاز می‌باشد.</p>
												</div>
												<div class="form-group <?php if(!empty($conf_passwordErr)) : echo 'has-error'; endif; ?>">
													<label for="aaconf_password"><?php if(!empty($conf_passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$conf_passwordErr; else : echo "* تکرار رمز عبور"; endif; ?></label>
													<input type="password" class="form-control" style="direction:ltr;" name="conf_password" value="<?php echo $conf_password; ?>" id="aaconf_password" form="admin-pass<?php echo $row["id"]; ?>">
													<p class="help-block">رمز عبور را دوباره وارد نمایید.</p>
												</div>												
											</div>
											<div class="modal-footer">
												<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" form="admin-pass<?php echo $row["id"]; ?>">
												<input type="hidden" name="username" value="<?php echo $row["username"]; ?>" form="admin-pass<?php echo $row["id"]; ?>">
												<input type="hidden" name="passChange" value="1" form="admin-pass<?php echo $row["id"]; ?>">
												<input type="submit" class="btn btn-primary" value="ثبت تغییر" form="admin-pass<?php echo $row["id"]; ?>">
											</div>
										</form>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div><!-- /.example-modal -->						
						
						<div class="example-modal">
							<div class="modal fade" id="delete<?php echo $row["id"]; ?>" tabindex="-1" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">پیام</h4>
										</div>
										<div class="modal-body">
											<p>آیا واقعا می‌خواهید مدیر <?php echo $row["username"]; ?> را حذف کنید؟</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" data-dismiss="modal">خیر</button>
											<form data-pjax method="post" action="<?php echo MAIN_URL.'admin/admins';?>" id="admin-del<?php echo $row["id"]; ?>" role="form">
												<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" form="admin-del<?php echo $row["id"]; ?>">
												<input type="hidden" name="username" value="<?php echo $row["username"]; ?>" form="admin-del<?php echo $row["id"]; ?>">
												<input type="hidden" name="delete" value="1" form="admin-del<?php echo $row["id"]; ?>">
												<input type="submit" class="btn btn-primary" value="بله" form="admin-del<?php echo $row["id"]; ?>">
											</form>
										</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div><!-- /.example-modal -->
						
						<?php endwhile;	?>					  
					</tbody>
				</table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	
	</section><!-- /.content -->	
	
	
	<?php endif; ?>
	
	</div><!-- /#pjax-container -->
</div><!-- /.content-wrapper -->
<?php include 'footer.php'; ?>