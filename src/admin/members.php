<?php
$title = 'اعضا';
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
	$usernameErr = $passwordErr = $conf_passwordErr = $firstnameErr = $lastnameErr = $father_nameErr = $meli_numErr = $birth_dateErr = $mobileErr = $emailErr = $bank_nameErr = $card_numErr = $hesab_numErr = $picErr = $picSuc = $join_dateErr = $amSuc = $amErr = "";
	
	if ($_REQUEST['op']=='add') {
		$id = $username = $password = $conf_password = $firstname = $lastname = $father_name = $meli_num = $birth_date = $mobile = $email = $bank_name = $card_num = $hesab_num = $pic = $_SESSION['ampic'] = $join_date = "";
	}
	else {
		$result	= $conn->query("SELECT * FROM sgh_members WHERE id=".test_input($_REQUEST['id'])." LIMIT 1");
		$row	= $result->fetch_assoc();
		extract($row);
		$birth_date			= str_replace("-", "/", $birth_date);
		$join_date			= str_replace("-", "/", $join_date);
		$_SESSION['ampic']	= $pic;
	}
	
	if (isset($_POST["amsubmit"])) {

		if (empty($_POST["username"])) {
			$usernameErr = "نام کاربری را وارد کنید";
		}
		else {
			$username = test_input($_POST["username"]);
			if (!preg_match("/^[a-z\d_-]{3,40}$/i",$username)) {
				$usernameErr = "نام کاربری وارد شده صحیح نیست";
			}
			else {
				$result = ($_REQUEST['op']=='edit') ? $conn->query("SELECT username, id FROM sgh_members WHERE username='$username' AND id!=".test_input($_REQUEST['id'])." LIMIT 1") : $conn->query("SELECT username, id FROM sgh_members WHERE username='$username' LIMIT 1");
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
		
		if (empty($_POST["firstname"])) {
			$firstnameErr = "نام را وارد کنید";
		}
		else {
			$firstname = test_input($_POST["firstname"]);
			if (!preg_match("@^[\s\x{0621}-\x{063A}\x{0640}-\x{0691}\x{0698}-\x{06D2}()]+$@u",$firstname)) {
				$firstnameErr = "نام وارد شده صحیح نیست";
			}
		}

		if (empty($_POST["lastname"])) {
			$lastnameErr = "نام خانوادگی را وارد کنید";
		}
		else {
			$lastname = test_input($_POST["lastname"]);
			if (!preg_match("@^[\s\x{0621}-\x{063A}\x{0640}-\x{0691}\x{0698}-\x{06D2}()]+$@u",$lastname)) {
				$lastnameErr = "نام خانوادگی وارد شده صحیح نیست";
			}					
		}
		
		if (!empty($_POST["father_name"])) {
			$father_name = test_input($_POST["father_name"]);
			if (!preg_match("@^[\s\x{0621}-\x{063A}\x{0640}-\x{0691}\x{0698}-\x{06D2}()]+$@u",$father_name)) {
				$father_nameErr = "نام وارد شده صحیح نیست";
			}
		}
		
		if (!empty($_POST["meli_num"])) {
			$meli_num = test_input($_POST["meli_num"]);
			if (!preg_match("/^[0-9]{10}$/",$meli_num)) {
				$meli_numErr = "شماره ملی وارد شده صحیح نیست";
			}
		}
		
		if (!empty($_POST["birth_date"])) {
			$birth_date = test_input($_POST["birth_date"]);
			if (!preg_match("/^[0-9\/]{10}$/",$birth_date)) {
				$birth_dateErr = "تاریخ وارد شده صحیح نیست";
			}					
		}	
		
		if (!empty($_POST["mobile"])) {
			$mobile = test_input($_POST["mobile"]);
			if (!preg_match("/^[0-9]{11}$/",$mobile)) {
				$mobileErr = "شماره موبایل وارد شده صحیح نیست";
			}
		}
		
		if (!empty($_POST["email"])) {
			$email = test_input($_POST["email"]);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "ایمیل وارد شده صحیح نیست";
			}					
		}
		
		if (!empty($_POST["bank_name"])) {
			$bank_name = test_input($_POST["bank_name"]);
			if (!preg_match("@^[\s\x{0621}-\x{063A}\x{0640}-\x{0691}\x{0698}-\x{06D2}]+$@u",$bank_name)) {
				$bank_nameErr = "نام وارد شده صحیح نیست";
			}
		}
		
		if (!empty($_POST["card_num"])) {
			$card_num = test_input($_POST["card_num"]);
			if (!preg_match("/^[0-9]{16}$/",$card_num)) {
				$card_numErr = "شماره کارت وارد شده صحیح نیست";
			}
		}
		
		if (!empty($_POST["hesab_num"])) {
			$hesab_num = test_input($_POST["hesab_num"]);
			if (!preg_match("/^[0-9]{16}$/",$hesab_num)) {
				$hesab_numErr = "شماره حساب وارد شده صحیح نیست";
			}
		}
		
		if (!empty($_POST["join_date"])) {
			$join_date = test_input($_POST["join_date"]);
			if (!preg_match("/^[0-9\/]{10}$/",$join_date)) {
				$join_dateErr = "تاریخ وارد شده صحیح نیست";
			}					
		}
		
		if (empty($usernameErr) && empty($passwordErr) && empty($conf_passwordErr) && empty($firstnameErr) &&
			empty($lastnameErr) && empty($father_nameErr) && empty($meli_numErr) && empty($birth_dateErr) && empty($mobileErr) &&
			empty($emailErr) && empty($bank_nameErr) && empty($card_numErr) && empty($hesab_numErr) && empty($join_dateErr)) 
		{

			if (isset($_FILES["pic"])) {

				$target_dir = "../uploads/avatars/members/";
				$target_file = $target_dir . basename($_FILES["pic"]["name"]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);				
		
				if ( !empty(basename($_FILES["pic"]["name"])) && !empty($_SESSION['ampic']) ) {
					@unlink($_SESSION['ampic']);
					$_SESSION['ampic'] = "";
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
												$_SESSION['ampic'] = str_replace('../', '', $target_file);
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
		
		if (empty($usernameErr) && empty($passwordErr) && empty($conf_passwordErr) && empty($firstnameErr) &&
			empty($lastnameErr) && empty($father_nameErr) && empty($meli_numErr) && empty($birth_dateErr) && empty($mobileErr) &&
			empty($emailErr) && empty($bank_nameErr) && empty($card_numErr) && empty($hesab_numErr) && empty($join_dateErr) && empty($picErr)) 
		{
			$pic = $_SESSION['ampic'];
			
			if ($_REQUEST['op']=='add') {
				$sql = "INSERT INTO sgh_members (username, password, firstname, lastname, father_name, meli_num, birth_date, mobile, email, bank_name, card_num, hesab_num, join_date, pic) 
				VALUES ('$username', '".md5($password)."', '$firstname', '$lastname', '$father_name', '$meli_num', '$birth_date', '$mobile', '$email', '$bank_name', '$card_num', '$hesab_num', '$join_date', '$pic')";
			}
			else {
				$sql = "UPDATE sgh_members SET username='$username', firstname='$firstname', lastname='$lastname', father_name='$father_name', meli_num='$meli_num', birth_date='$birth_date', mobile='$mobile', email='$email', bank_name='$bank_name', card_num='$card_num', hesab_num='$hesab_num', join_date='$join_date', pic='$pic' WHERE id=".test_input($_REQUEST['id']);				
			}
			
			if ($conn->query($sql) === TRUE) {
				$amSuc = ($_REQUEST['op']=='add') ? "کاربر ".$username." با موفقیت افزوده شد." : "کاربر ".$username." با موفقیت ویرایش گردید.";
				if ($_REQUEST['op']=='add') {
					$username = $password = $conf_password = $firstname = $lastname = $father_name = $meli_num = $birth_date = $mobile = $email = $bank_name = $card_num = $hesab_num = $pic = $_SESSION['ampic'] = $picSuc = $join_date = "";
				}	
			} 
			else {
				$amErr = ($_REQUEST['op']=='add') ? "مشکلی در ثبت کاربر ".$username." به وجود آمده است." : "مشکلی در ویرایش کاربر ".$username." به وجود آمده است.";
			}
		}
		
	}
	
	if (isset($_POST["delpic"])) {
		@unlink('../'.$_SESSION['ampic']);
		$_SESSION['ampic'] = "";
		
		$pic = $_SESSION['ampic'];
		
		if ($_REQUEST['op']=='edit') {
			$sql = "UPDATE sgh_members SET pic='$pic' WHERE id=".test_input($_REQUEST['id']);
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
			<small><?php if($_REQUEST['op']=='add') : echo "افزودن عضو جدید"; else : echo "ویرایش اعضا"; endif; ?></small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<?php if(!empty($amSuc)) : ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-check"></i>پیام!</h4>
			<?php echo $amSuc; ?>
		</div>
		<?php elseif(!empty($amErr)) : ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-ban"></i>پیام!</h4>
			<?php echo $amErr; ?>
		</div>		
		<?php endif; ?>
		
		<!-- general form elements -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php if($_REQUEST['op']=='add') : echo "فرم عضویت"; else : echo "فرم ویرایش عضو"; endif; ?></h3>
			</div><!-- /.box-header -->
			
			<!-- form start -->
			<form data-pjax method="post" action="<?php if($_REQUEST['op']=='add') : echo MAIN_URL.'admin/members?op=add'; else : echo MAIN_URL.'admin/members?op=edit&id='.test_input($_REQUEST['id']); endif; ?>" enctype="multipart/form-data" role="form">
				<div class="box-body">
					<div class="form-group <?php if(!empty($usernameErr)) : echo 'has-error'; endif; ?>">
						<label for="amusername"><?php if(!empty($usernameErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$usernameErr; else : echo "* نام کاربری"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="username" value="<?php echo $username; ?>" id="amusername">
						<p class="help-block">استفاده از ارقام، حروف لاتین، "_" و "-" مجاز می‌باشد. حداقل 3 کاراکتر.</p>
					</div>
					<?php if ($_REQUEST['op']=='add') : ?>
					<div class="form-group <?php if(!empty($passwordErr)) : echo 'has-error'; endif; ?>">
						<label for="ampassword"><?php if(!empty($passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$passwordErr; else : echo "* رمز عبور"; endif; ?></label>
						<input type="password" class="form-control" style="direction:ltr;" name="password" value="<?php echo $password; ?>" id="ampassword">
						<p class="help-block">استفاده از تمام کاراکترها مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($conf_passwordErr)) : echo 'has-error'; endif; ?>">
						<label for="amconf_password"><?php if(!empty($conf_passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$conf_passwordErr; else : echo "* تکرار رمز عبور"; endif; ?></label>
						<input type="password" class="form-control" style="direction:ltr;" name="conf_password" value="<?php echo $conf_password; ?>" id="amconf_password">
						<p class="help-block">رمز عبور را دوباره وارد نمایید.</p>
					</div>
					<?php endif; ?>
					<div class="form-group <?php if(!empty($firstnameErr)) : echo 'has-error'; endif; ?>">
						<label for="amfirstname"><?php if(!empty($firstnameErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$firstnameErr; else : echo "* نام"; endif; ?></label>
						<input type="text" class="form-control" name="firstname" value="<?php echo $firstname; ?>" id="amfirstname">
						<p class="help-block">تنها کاراکترهای فارسی مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($lastnameErr)) : echo 'has-error'; endif; ?>">
						<label for="amlastname"><?php if(!empty($lastnameErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$lastnameErr; else : echo "* نام خانوادگی"; endif; ?></label>
						<input type="text" class="form-control" name="lastname" value="<?php echo $lastname; ?>" id="amlastname">
						<p class="help-block">تنها کاراکترهای فارسی مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($father_nameErr)) : echo 'has-error'; endif; ?>">
						<label for="amfather_name"><?php if(!empty($father_namErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$father_namErr; else : echo "نام پدر"; endif; ?></label>
						<input type="text" class="form-control" name="father_name" value="<?php echo $father_name; ?>" id="amfather_name">
						<p class="help-block">تنها کاراکترهای فارسی مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($meli_numErr)) : echo 'has-error'; endif; ?>">
						<label for="ammeli_num"><?php if(!empty($meli_numErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$meli_numErr; else : echo "شماره ملی"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="meli_num" value="<?php echo $meli_num; ?>" id="ammeli_num">
						<p class="help-block">شماره ملی باید 10 رقمی باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($birth_dateErr)) : echo 'has-error'; endif; ?>">
						<div class="row">
							<div class="col-md-4">
								<label for="ambirth_date"><?php if(!empty($birth_dateErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$birth_dateErr; else : echo "تاریخ تولد"; endif; ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" name="birth_date" value="<?php echo $birth_date; ?>" id="ambirth_date">
								</div>
							</div>
						</div>
					</div>					
					<div class="form-group <?php if(!empty($mobileErr)) : echo 'has-error'; endif; ?>">
						<label for="ammobile"><?php if(!empty($mobileErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$mobileErr; else : echo "شماره موبایل"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="mobile" value="<?php echo $mobile; ?>" id="ammobile">
						<p class="help-block">شماره موبایل باید 11 رقمی باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($emailErr)) : echo 'has-error'; endif; ?>">
						<label for="amemail"><?php if(!empty($emailErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$emailErr; else : echo "ایمیل"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="email" value="<?php echo $email; ?>" id="amemail">
						<p class="help-block">مثال: example@gmail.com</p>
					</div>
					<div class="form-group <?php if(!empty($bank_nameErr)) : echo 'has-error'; endif; ?>">
						<label for="ambank_name"><?php if(!empty($bank_nameErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$bank_nameErr; else : echo "نام بانک"; endif; ?></label>
						<input type="text" class="form-control" name="bank_name" value="<?php echo $bank_name; ?>" id="ambank_name">
						<p class="help-block">تنها کاراکترهای فارسی مجاز می‌باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($card_numErr)) : echo 'has-error'; endif; ?>">
						<label for="amcard_num"><?php if(!empty($card_numErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$card_numErr; else : echo "شماره کارت"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="card_num" value="<?php echo $card_num; ?>" id="amcard_num">
						<p class="help-block">شماره کارت باید 16 رقم باشد.</p>
					</div>
					<div class="form-group <?php if(!empty($hesab_numErr)) : echo 'has-error'; endif; ?>">
						<label for="amhesab_num"><?php if(!empty($hesab_numErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$hesab_numErr; else : echo "شماره حساب"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="hesab_num" value="<?php echo $hesab_num; ?>" id="amhesab_num">
						<p class="help-block">شماره حساب باید 13 رقم باشد.</p>
					</div>					
					<div class="form-group <?php if(!empty($birth_dateErr)) : echo 'has-error'; endif; ?>">
						<div class="row">
							<div class="col-md-4">
								<label for="amjoin_date"><?php if(!empty($birth_dateErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$join_dateErr; else : echo "تاریخ عضویت"; endif; ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" name="join_date" value="<?php echo $join_date; ?>" id="amjoin_date">
								</div>
							</div>
						</div>
					</div>						
					<div class="form-group <?php if(!empty($picErr)) : echo 'has-error'; elseif(!empty($picSuc)) : echo 'has-success'; endif; ?>">
						<div class="btn btn-default btn-file">
							<i class="fa fa-paperclip"></i> تصویر پروفایل
							<input type="file" name="pic" id="ampic">
						</div>
						<p class="help-block"><?php if(!empty($picErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$picErr; elseif(!empty($picSuc)) : echo '<i class="fa fa-check"></i> '.$picSuc;  else : echo "حداکثر سایز مجاز 100 کیلوبایت می‌باشد."; endif; ?></p>
						<?php if(!empty($_SESSION['ampic'])) : ?>
							<div class="avatar-groupe">
								<img id="img-up" src="<?php echo MAIN_URL; ?><?php echo $_SESSION['ampic']; ?>" class="img-circle" width="100px" height="100px">
								<button class="btn btn-danger btn-xs img-del-btn" data-toggle="tooltip" data-placement="top" title="حذف" value=""><i class="fa fa-trash-o"></i></button>
							</div>
						<?php endif; ?>
					</div>
				</div><!-- /.box-body -->

				<div class="box-footer">
					<input type="hidden" name="amsubmit" value="1">
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
			<small>لیست اعضا</small>
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
				$sql = "UPDATE sgh_members SET password='".md5($password)."' WHERE id=".test_input($_POST["id"]);
				if ($conn->query($sql) === TRUE) {
					$MChangePassSucc = "رمز عبور کاربر ".$_POST["username"]." با موفقیت تغییر یافت.";
					$password = $conf_password = "";
					
				} else {
					$MChangePassErr = "مشکلی در تغییر رمز عبور کاربر ".$_POST["username"]." به وجود آمده است.";
				}
			}		
		}		
		
		if (isset($_POST["delete"])) {
			$conn->query("DELETE FROM sgh_transactions WHERE member_id=".test_input($_POST["id"]));
			$conn->query("DELETE FROM sgh_loans WHERE member_id=".test_input($_POST["id"]));
			$resultdel = $conn->query("SELECT * FROM sgh_members WHERE id=".test_input($_POST["id"])." LIMIT 1");
			$rdel = $resultdel->fetch_assoc();
			@unlink('../'.$rdel['pic']);
			$sql = "DELETE FROM sgh_members WHERE id=".test_input($_POST["id"]);
			if ($conn->query($sql) === TRUE) {
				$deleteSuc = "کاربر ".$_POST["username"]." با موفقیت حذف شد.";
			} else {
				$deleteErr = "مشکلی در حذف کاربر ".$_POST["username"]." به وجود آمده است.";
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
				<h3 class="box-title">لیست اعضای صندوق</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped turn-num">
					<thead>
						<tr>
							<th>نام کاربری</th>
							<th>نام</th>
							<th>نام خانوادگی</th>
							<th>نام پدر</th>
							<th>موجودی</th>
							<th>بدهی</th>
							<th>امکانات</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
						$result = $conn->query("SELECT * FROM sgh_members");
						while($row = $result->fetch_assoc()) :
						?>
						
						<tr>
							<td><?php echo $row["username"]; ?></td>
							<td><?php echo $row["firstname"]; ?></td>
							<td><?php echo $row["lastname"]; ?></td>
							<td><?php echo $row["father_name"]; ?></td>
							<td>
								<?php
								$result1 = $conn->query("SELECT SUM(amount) AS psp_sum FROM sgh_transactions WHERE (type='پرداخت پاره‌سهم' OR type='سایر (پرداخت)') AND member_id=".$row["id"]);		
								$rowt1 = $result1->fetch_assoc();
								$psp_sum = $rowt1['psp_sum'];
								
								$result1 = $conn->query("SELECT SUM(amount) AS sd_sum FROM sgh_transactions WHERE type='سایر (دریافت)' AND member_id=".$row["id"]);
								$rowt1 = $result1->fetch_assoc();
								$sd_sum = $rowt1['sd_sum'];
								
								echo number_format($psp_sum - $sd_sum);
								?>
							</td>
							<td>
								<?php
								$result2 = $conn->query("SELECT SUM(amount) AS pq_sum FROM sgh_transactions WHERE type='پرداخت قسط' AND member_id=".$row["id"]);		
								$rowt2 = $result2->fetch_assoc(); 
								$pq_sum = $rowt2['pq_sum'];
								
								$result2 = $conn->query("SELECT SUM(amount) AS dv_sum FROM sgh_loans WHERE member_id=".$row["id"]);		
								$rowt2 = $result2->fetch_assoc();
								$dv_sum = $rowt2['dv_sum'];
								
								echo number_format($dv_sum - $pq_sum);
								?>
							</td>
							<td>
								<a data-pjax href="<?php echo MAIN_URL;?>admin/members?op=edit&id=<?php echo $row["id"]; ?>" class="btn btn-default btn-sm" role="button" data-toggle="tooltip" data-placement="top" title="ویرایش"><i class="fa fa-edit"></i></a>
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
											<h4 class="modal-title">تغییر رمز عبور کاربر <?php echo $row["username"]; ?></h4>
										</div>
										<form data-pjax method="post" action="<?php echo MAIN_URL;?>admin/members" id="modal-details<?php echo $row["id"]; ?>" role="form">
											<div class="modal-body">
												<div class="form-group <?php if(!empty($passwordErr)) : echo 'has-error'; endif; ?>">
													<label for="ampassword"><?php if(!empty($passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$passwordErr; else : echo "* رمز عبور"; endif; ?></label>
													<input type="password" class="form-control" style="direction:ltr;" name="password" value="<?php echo $password; ?>" id="ampassword" form="modal-details<?php echo $row["id"]; ?>">
													<p class="help-block">استفاده از تمام کاراکترها مجاز می‌باشد.</p>
												</div>
												<div class="form-group <?php if(!empty($conf_passwordErr)) : echo 'has-error'; endif; ?>">
													<label for="amconf_password"><?php if(!empty($conf_passwordErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$conf_passwordErr; else : echo "* تکرار رمز عبور"; endif; ?></label>
													<input type="password" class="form-control" style="direction:ltr;" name="conf_password" value="<?php echo $conf_password; ?>" id="amconf_password" form="modal-details<?php echo $row["id"]; ?>">
													<p class="help-block">رمز عبور را دوباره وارد نمایید.</p>
												</div>												
											</div>
											<div class="modal-footer">
												<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" form="modal-details<?php echo $row["id"]; ?>">
												<input type="hidden" name="username" value="<?php echo $row["username"]; ?>" form="modal-details<?php echo $row["id"]; ?>">
												<input type="hidden" name="passChange" value="1" form="modal-details<?php echo $row["id"]; ?>">
												<input type="submit" class="btn btn-primary" value="ثبت تغییر" form="modal-details<?php echo $row["id"]; ?>">
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
											<p>آیا واقعا می‌خواهید کاربر <?php echo $row["username"]; ?> را حذف کنید؟</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" data-dismiss="modal">خیر</button>
											<form data-pjax method="post" action="<?php echo MAIN_URL;?>admin/members" id="modal-del<?php echo $row["id"]; ?>" role="form">
												<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" form="modal-del<?php echo $row["id"]; ?>">
												<input type="hidden" name="username" value="<?php echo $row["username"]; ?>" form="modal-del<?php echo $row["id"]; ?>">
												<input type="hidden" name="delete" value="1" form="modal-del<?php echo $row["id"]; ?>">
												<input type="submit" class="btn btn-primary" value="بله" form="modal-del<?php echo $row["id"]; ?>">
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