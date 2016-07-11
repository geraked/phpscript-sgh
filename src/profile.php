<?php
$title = 'مشخصات';
include 'header.php';
include 'sidebar.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<div id="loading"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>

	<div data-pjax id="pjax-container">

	<?php
	// define variables and set to empty values
	$usernameErr = $firstnameErr = $lastnameErr = $father_nameErr = $meli_numErr = $birth_dateErr = $mobileErr = $emailErr = $bank_nameErr = $card_numErr = $hesab_numErr = $picErr = $picSuc = $amSuc = $amErr = "";

	$result	= $conn->query("SELECT * FROM sgh_members WHERE id=".$user['id']." LIMIT 1");
	$row	= $result->fetch_assoc();
	extract($row);
	$birth_date 		= str_replace("-", "/", $birth_date);
	$_SESSION['ampic']	= $pic;
	
	if (isset($_POST["amsubmit"])) {
		
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
		
		if (empty($usernameErr) && empty($firstnameErr) &&
			empty($lastnameErr) && empty($father_nameErr) && empty($meli_numErr) && empty($birth_dateErr) && empty($mobileErr) &&
			empty($emailErr) && empty($bank_nameErr) && empty($card_numErr) && empty($hesab_numErr))
		{

			if (isset($_FILES["pic"])) {

				$target_dir = "uploads/avatars/members/";
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
												$_SESSION['ampic'] = $target_file;
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
		
		if (empty($usernameErr) && empty($firstnameErr) &&
			empty($lastnameErr) && empty($father_nameErr) && empty($meli_numErr) && empty($birth_dateErr) && empty($mobileErr) &&
			empty($emailErr) && empty($bank_nameErr) && empty($card_numErr) && empty($hesab_numErr) && empty($picErr)) 
		{
			$pic = $_SESSION['ampic'];
			
			$sql = "UPDATE sgh_members SET firstname='$firstname', lastname='$lastname', father_name='$father_name', meli_num='$meli_num', birth_date='$birth_date', mobile='$mobile', email='$email', bank_name='$bank_name', card_num='$card_num', hesab_num='$hesab_num', pic='$pic' WHERE id=".$user['id'];				
			
			if ($conn->query($sql) === TRUE) {
				$amSuc = "مشخصات شما با موفقیت ویرایش گردید.";
			} 
			else {
				$amErr = "مشکلی در ویرایش مشخصات به وجود آمده است.";
			}
		}
		
	}

	if (isset($_POST["delpic"])) {
		@unlink($_SESSION['ampic']);
		$_SESSION['ampic'] = "";
		
		$pic = $_SESSION['ampic'];
		
		$sql = "UPDATE sgh_members SET pic='$pic' WHERE id=".$user['id'];
		if ($conn->query($sql) === TRUE) {
			$picSuc = "تصویر با موفقیت حذف گردید.";
		}
		else {
			$picErr = "مشکلی در حذف تصویر به وجود آمده است.";
		}
	}	
	?>	
	
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $title; ?>
			<small>ویرایش مشخصات</small>
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
				<h3 class="box-title">فرم ویرایش مشخصات</h3>
			</div><!-- /.box-header -->
			
			<!-- form start -->
			<form data-pjax method="post" action="<?php echo MAIN_URL; ?>profile" enctype="multipart/form-data" role="form">
				<div class="box-body">
					<div class="form-group <?php if(!empty($usernameErr)) : echo 'has-error'; endif; ?>">
						<label for="amusername"><?php if(!empty($usernameErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$usernameErr; else : echo "* نام کاربری"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="username" value="<?php echo $username; ?>" id="amusername" disabled>
						<p class="help-block">استفاده از ارقام، حروف لاتین، "_" و "-" مجاز می‌باشد. حداقل 3 کاراکتر.</p>
					</div>
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
									<input type="text" class="form-control" name="birth_date" value="<?php if(empty($birth_date)) : echo '1300/01/01'; else : echo $birth_date; endif;?>" id="ambirth_date">
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
					<button type="submit" class="btn btn-primary">ویرایش اطلاعات</button>
				</div>
			</form>
		</div><!-- /.box -->
		
	</section><!-- /.content -->
	
	</div><!-- /#pjax-container -->
</div><!-- /.content-wrapper -->
<?php include 'footer.php'; ?>