<?php
$title = 'تراکنش‌ها';
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
	$member_idErr = $typeErr = $loan_idErr = $amountErr = $create_dateErr = $descriptionErr = $atSuc = $atErr = "";
	
	if ($_REQUEST['op']=='add') {
		$member_id = $type = $loan_id = $amount = $create_date = $description = "";
	}
	else {
		$result	= $conn->query("SELECT * FROM sgh_transactions WHERE id=".test_input($_REQUEST['id'])." LIMIT 1");
		$row	= $result->fetch_assoc();
		extract($row);
		$amount		 = number_format($amount);
		$create_date = str_replace("-", "/", $create_date);
	}	

	if (isset($_POST["atsubmit"])) {
		if (empty($_POST["member_id"])) {
			$member_idErr = "عضو مورد نظر را انتخاب کنید";
		}
		else {
			$member_id = test_input($_POST["member_id"]);
		}
		
		if (empty($_POST["type"])) {
			$typeErr = "نوع تراکنش را مشخص کنید";
		}
		else {
			$type = test_input($_POST["type"]);
		}

		if ( !empty($_POST["type"]) && ($_POST["type"]=="پرداخت قسط" || $_POST["type"]=="دریافت وام") && empty($_POST["loan_id"]) ) {
			$loan_idErr = "وام مورد نظر را انتخاب کنید";
		}
		elseif ($_POST["type"]=="پرداخت قسط" || $_POST["type"]=="دریافت وام") {
			$loan_id = test_input($_POST["loan_id"]);
		}
		else {
			$loan_id = "";
		}
		
		if (empty($_POST["amount"])) {
			$amountErr = "مبلغ تراکنش را وارد نمایید (ریال)";
		}
		else {
			$amount = test_input($_POST["amount"]);
			if (!preg_match("/^[1-9][0-9\,]{0,11}$/",$amount)) {
				$amountErr = "مبلغ وارد شده صحیح نیست";
			}
		}
		
		if (empty($_POST["create_date"])) {
			$create_dateErr = "تاریخ انجام تراکنش را وارد کنید";		
		}
		else {
			$create_date = test_input($_POST["create_date"]);
			if (!preg_match("/^[0-9\/]{10}$/",$create_date)) {
				$create_dateErr = "تاریخ وارد شده صحیح نیست";
			}
		}	

		if (!empty($_POST["description"])) {
			$description = test_input($_POST["description"]);
		}

		if ( empty($member_idErr) && empty($amountErr) && empty($loan_idErr) && empty($create_dateErr) && empty($descriptionErr) ) {
			if ($_REQUEST['op']=='add') {
				$sql = "INSERT INTO sgh_transactions (member_id, type, loan_id, amount, create_date, description) 
				VALUES ('$member_id', '$type', '$loan_id', '".str_replace(",", "", $amount)."', '$create_date', '$description')";
			}
			else {
				$sql = "UPDATE sgh_transactions SET member_id='$member_id', type='$type', loan_id='$loan_id', amount='".str_replace(",", "", $amount)."', create_date='$create_date', description='$description' WHERE id=".test_input($_REQUEST['id']);				
			}			
			
			if ($conn->query($sql) === TRUE) {
				$atSuc = ($_REQUEST['op']=='add') ? "تراکنش جدید با موفقیت ثبت گردید." : "تراکنش مورد نظر با موفقیت ویرایش گردید.";
				if ($_REQUEST['op']=='add') {
					$member_id = $type = $loan_id = $amount = $create_date = $description = "";
				}
			} 
			else {
				$atErr = ($_REQUEST['op']=='add') ? "مشکلی در ثبت تراکنش به وجود آمده است." : "مشکلی در ویرایش تراکنش به وجود آمده است.";
			}
		}
	}
	?>	
	
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $title; ?>
			<small><?php if($_REQUEST['op']=='add') : echo "ثبت تراکنش جدید"; else : echo "ویرایش تراکنش‌ها"; endif; ?></small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<?php if(!empty($atSuc)) : ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-check"></i>پیام!</h4>
			<?php echo $atSuc; ?>
		</div>
		<?php elseif(!empty($atErr)) : ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-ban"></i>پیام!</h4>
			<?php echo $atErr; ?>
		</div>		
		<?php endif; ?>
		
		<!-- general form elements -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php if($_REQUEST['op']=='add') : echo "فرم ثبت تراکنش"; else : echo "فرم ویرایش تراکنش"; endif; ?></h3>
			</div><!-- /.box-header -->
			<div id="result"></div>
			<!-- form start -->
			<form data-pjax method="post" action="<?php if($_REQUEST['op']=='add') : echo MAIN_URL.'admin/transactions?op=add'; else : echo MAIN_URL.'admin/transactions?op=edit&id='.$_REQUEST['id']; endif; ?>" enctype="multipart/form-data" role="form">
				<div class="box-body">
					<div id="atmember_id" class="form-group <?php if(!empty($member_idErr)) : echo 'has-error'; endif; ?>">
						<label><?php if(!empty($member_idErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$member_idErr; else : echo "* انتخاب عضو"; endif; ?></label>
						<select class="form-control select2" name="member_id" style="width:100%;">
							<?php if(empty($member_id)) : ?><option value="" selected="selected">انتخاب کنید</option><?php endif; ?>
							<?php
							$result = $conn->query("SELECT id, username, firstname, lastname FROM sgh_members");
							while($row = $result->fetch_assoc()) :
							?>
							<option value="<?php echo $row["id"]; ?>" <?php if($member_id==$row["id"]) : ?>selected="selected"<?php endif; ?> ><?php echo $row["username"].'- '.$row["firstname"].' '.$row["lastname"]; ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div id="attype" class="form-group <?php if(!empty($typeErr)) : echo 'has-error'; endif; ?>" style="display:none;">
						<label><?php if(!empty($typeErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$typeErr; else : echo "* نوع تراکنش"; endif; ?></label>
						<select class="form-control select2" name="type" style="width:100%;">
							<?php if(empty($type)) : ?><option value="" selected="selected">انتخاب کنید</option><?php endif; ?>
							<option value="پرداخت پاره‌سهم" <?php if($type=="پرداخت پاره‌سهم") : echo 'selected="selected"'; endif;?>>پرداخت پاره‌سهم</option>
							<?php 
							$result = $conn->query("SELECT * FROM sgh_loans WHERE member_id='$member_id' AND status='1'");
							while($row = $result->fetch_assoc()) : 
								if ($result->num_rows > 0) :
							?>
							<option value="پرداخت قسط" <?php if($type=="پرداخت قسط") : echo 'selected="selected"'; endif;?>>پرداخت قسط</option>
							<option value="دریافت وام" <?php if($type=="دریافت وام") : echo 'selected="selected"'; endif;?>>دریافت وام</option>
							<?php 
								endif;
							endwhile;
							?>
							<option value="سایر (پرداخت)" <?php if($type=="سایر (پرداخت)") : echo 'selected="selected"'; endif;?>>سایر (پرداخت)</option>
							<option value="سایر (دریافت)" <?php if($type=="سایر (دریافت)") : echo 'selected="selected"'; endif;?>>سایر (دریافت)</option>
						</select>
					</div>
					<div id="atloan_id" class="form-group <?php if(!empty($loan_idErr)) : echo 'has-error'; endif; ?>" style="display:none;">
						<label><?php if(!empty($loan_idErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$loan_idErr; else : echo "* انتخاب وام"; endif; ?></label>
						<select class="form-control select2" name="loan_id" style="width:100%;">
							<?php if(empty($loan_id)) : ?><option value="" selected="selected">انتخاب کنید</option><?php endif; ?>
							<?php
							$result = $conn->query("SELECT * FROM sgh_loans WHERE member_id='$member_id' AND status='1'");
							while($row = $result->fetch_assoc()) : 
								$installment_amount = $row["amount"] / $row["installment_num"];
							?>
							<option value="<?php echo $row["id"]; ?>" <?php if($loan_id==$row["id"]) : echo 'selected="selected"'; endif;?>><?php echo $row["create_date"].'- '.number_format($row["amount"]).'- '.$row["installment_num"].' ماهه- '.number_format($installment_amount); ?></option>
							<?php endwhile; ?>
						</select>
					</div>					
					<div id="atamount" class="form-group <?php if(!empty($amountErr)) : echo 'has-error'; endif; ?>" style="display:none;">
						<label><?php if(!empty($amountErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$amountErr; else : echo "* مبلغ تراکنش (ریال)"; endif; ?></label>
						<input type="text" class="form-control" onkeyup="javascript:this.value=addComma(this.value);" style="direction:ltr;" name="amount" value="<?php echo $amount; ?>">
						<p class="help-block">ارقام 9-0 مجاز می‌باشد. حداکثر 10 رقم.</p>
					</div>
					<div id="atcreate_date" class="form-group <?php if(!empty($create_dateErr)) : echo 'has-error'; endif; ?>" style="display:none;">
						<div class="row">
							<div class="col-md-4">
								<label><?php if(!empty($create_dateErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$create_dateErr; else : echo "* تاریخ انجام تراکنش"; endif; ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" name="create_date" value="<?php echo $create_date; ?>" >
								</div>
							</div>
						</div>
					</div>
					<div id="atdescription" class="form-group <?php if(!empty($descriptionErr)) : echo 'has-error'; endif; ?>" style="display:none;">
						<label for="aldescription"><?php if(!empty($descriptionErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$descriptionErr; else : echo "توضیحات"; endif; ?></label>
						<textarea class="form-control" name="description" rows="3" placeholder="توضیحات مورد نظر را وارد نمایید..."><?php echo $description; ?></textarea>
					</div>                    
				</div><!-- /.box-body -->

				<div id="atsubmit" class="box-footer" style="display:none;">
					<input type="hidden" name="atsubmit" value="1">
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
			<small>لیست تراکنش‌ها</small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<?php 
		$deleteSuc = $deleteErr = "";

		if (isset($_POST["delete"])) {
			$sql = "DELETE FROM sgh_transactions WHERE id=".test_input($_POST["id"]);
			if ($conn->query($sql) === TRUE) {
				$deleteSuc = "تراکنش مورد نظر با موفقیت حذف شد.";
			} else {
				$deleteErr = "مشکلی در حذف تراکنش به وجود آمده است.";
			}			
		}		
		
		
		$member_id = $type = $dstart = $dend = "";
		$sql = "SELECT * FROM sgh_transactions";
		
		if (isset($_POST["ltsearch"])) {		
			$member_id	= test_input($_POST["member_id"]);
			$type		= test_input($_POST["type"]);
			$dstart		= test_input(str_replace("/", "-", $_POST["dstart"]));
			$dend		= test_input(str_replace("/", "-", $_POST["dend"]));
			
			switch(true) {
				// Part 1
				case ($member_id!="همه" && $type!="همه" && $dstart!="" && $dend!=""):
					$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND type='$type' AND create_date between '$dstart' and '$dend'";
					break;
				case ($member_id=="همه" && $type!="همه" && $dstart!="" && $dend!=""):
					$sql = "SELECT * FROM sgh_transactions WHERE type='$type' AND create_date between '$dstart' and '$dend'";
					break;
				case ($member_id!="همه" && $type=="همه" && $dstart!="" && $dend!=""):
					$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND create_date between '$dstart' and '$dend'";
					break;
				case ($member_id!="همه" && $type!="همه" && $dstart=="" && $dend!=""):
					$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND type='$type' AND create_date<='$dend'";
					break;
				case ($member_id!="همه" && $type!="همه" && $dstart!="" && $dend==""):
					$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND type='$type' AND create_date>='$dstart'";
					break;
				// Part 2
				case ($member_id=="همه" && $type=="همه" && $dstart!="" && $dend!=""):
					$sql = "SELECT * FROM sgh_transactions WHERE create_date between '$dstart' and '$dend'";
					break;
				case ($member_id=="همه" && $type!="همه" && $dstart=="" && $dend!=""):
					$sql = "SELECT * FROM sgh_transactions WHERE type='$type' AND create_date<='$dend'";
					break;
				case ($member_id=="همه" && $type!="همه" && $dstart!="" && $dend==""):
					$sql = "SELECT * FROM sgh_transactions WHERE type='$type' AND create_date>='$dstart'";
					break;
				// Part 3
				case ($member_id!="همه" && $type=="همه" && $dstart=="" && $dend!=""):
					$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND create_date<='$dend'";
					break;
				case ($member_id!="همه" && $type=="همه" && $dstart!="" && $dend==""):
					$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND create_date>='$dstart'";
					break;
				// Part 4
				case ($member_id!="همه" && $type!="همه" && $dstart=="" && $dend==""):
					$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND type='$type'";
					break;
				// Part 5
				case ($member_id!="همه" && $type=="همه" && $dstart=="" && $dend==""):
					$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id'";
					break;
				case ($member_id=="همه" && $type!="همه" && $dstart=="" && $dend==""):
					$sql = "SELECT * FROM sgh_transactions WHERE type='$type'";
					break;
				case ($member_id=="همه" && $type=="همه" && $dstart!="" && $dend==""):
					$sql = "SELECT * FROM sgh_transactions WHERE create_date>='$dstart'";
					break;
				case ($member_id=="همه" && $type=="همه" && $dstart=="" && $dend!=""):
					$sql = "SELECT * FROM sgh_transactions WHERE create_date<='$dend'";
					break;		
				default:
					$sql = "SELECT * FROM sgh_transactions";
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
		<?php endif; ?>
		
		
		<div class="box box-solid">
			<div class="box-body">
				<form data-pjax method="post" action="<?php echo MAIN_URL; ?>admin/transactions" enctype="multipart/form-data" role="form">
					<div class="row">
						<div class="col-md-4 col-sm-6">					
							<div id="ltmember_id" class="form-group <?php if(!empty($member_idErr)) : echo 'has-error'; endif; ?>">
								<label><?php if(!empty($member_idErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$member_idErr; else : echo "انتخاب عضو"; endif; ?></label>
								<select class="form-control select2" name="member_id" style="width:100%;">
									<option value="همه" <?php if(empty($member_id) || $member_id=="همه") : echo 'selected="selected"'; endif;?>>همه</option>
									<?php
									$result = $conn->query("SELECT id, username, firstname, lastname FROM sgh_members");
									while($row = $result->fetch_assoc()) :
									?>
									<option value="<?php echo $row["id"]; ?>" <?php if($member_id==$row["id"]) : ?>selected="selected"<?php endif; ?> ><?php echo $row["username"].'- '.$row["firstname"].' '.$row["lastname"]; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						<div class="col-md-4 col-sm-6">
							<div id="lttype" class="form-group <?php if(!empty($typeErr)) : echo 'has-error'; endif; ?>">
								<label><?php if(!empty($typeErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$typeErr; else : echo "نوع تراکنش"; endif; ?></label>
								<select class="form-control select2" name="type" style="width:100%;">
									<option value="همه" <?php if(empty($type) || $type=="همه") : echo 'selected="selected"'; endif;?>>همه</option>
									<option value="پرداخت پاره‌سهم" <?php if($type=="پرداخت پاره‌سهم") : echo 'selected="selected"'; endif;?>>پرداخت پاره‌سهم</option>
									<option value="پرداخت قسط" <?php if($type=="پرداخت قسط") : echo 'selected="selected"'; endif;?>>پرداخت قسط</option>
									<option value="دریافت وام" <?php if($type=="دریافت وام") : echo 'selected="selected"'; endif;?>>دریافت وام</option>
									<option value="سایر (پرداخت)" <?php if($type=="سایر (پرداخت)") : echo 'selected="selected"'; endif;?>>سایر (پرداخت)</option>
									<option value="سایر (دریافت)" <?php if($type=="سایر (دریافت)") : echo 'selected="selected"'; endif;?>>سایر (دریافت)</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-9">
							<!-- Date range -->
							<div id="ltrange" class="form-group">
								<label>محدوده تاریخ</label>
								<div class="input-daterange input-group">
									<input type="text" class="form-control" name="dstart" value="<?php echo str_replace("-", "/", $dstart); ?>">
									<span class="input-group-addon">تا</span>
									<input type="text" class="form-control" name="dend" value="<?php echo str_replace("-", "/", $dend); ?>">
								</div>
							</div>
						</div>
						<div class="col-md-1 col-xs-3">
							<div class="form-group">
								<input type="hidden" name="ltsearch" value="1">
								<button type="submit" class="btn btn-default form-control" style="margin-top:25px;"><i class="icon fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</form>
			</div><!-- /.box-body -->
		</div><!-- /.box -->		

		
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">لیست تراکنش‌های صندوق</h3>
				<a target="_blank" href="<?php echo MAIN_URL;?>admin/transactions-pdf?<?php echo 'member_id='.$member_id.'&type='.$type.'&dstart='.$dstart.'&dend='.$dend; ?>" class="btn btn-default pull-left pdf-link" role="button" data-toggle="tooltip" data-placement="right" title="دریافت فایل PDF"><i class="icon fa fa-file-pdf-o"></i></a>
			</div><!-- /.box-header -->
			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>تاریخ</th>
							<th>تراکنش‌کننده</th>
							<th>نوع</th>
							<th>مبلغ</th>
							<th>توضیحات</th>
							<th>امکانات</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
						$result = $conn->query($sql);
						while($row = $result->fetch_assoc()) :
							$member_result = $conn->query("SELECT username, firstname, lastname FROM sgh_members WHERE id=".$row["member_id"]." LIMIT 1");
							$member = $member_result->fetch_assoc();							
						?>
						
						<tr>
							<td><?php echo str_replace("-", "/", $row["create_date"]); ?></td>
							<td><?php echo $member["username"].'- '.$member["firstname"].' '.$member["lastname"]; ?></td>
							<td><?php echo $row["type"]; ?></td>
							<td><?php echo number_format($row["amount"]); ?></td>
							<td><?php echo $row["description"]; ?></td>
							<td>
								<a data-pjax href="<?php echo MAIN_URL; ?>admin/transactions?op=edit&id=<?php echo $row["id"]; ?>" class="btn btn-default btn-sm" role="button" data-toggle="tooltip" data-placement="top" title="ویرایش"><i class="fa fa-edit"></i></a>
								<span data-toggle="modal" data-target="#delete<?php echo $row["id"]; ?>"><button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="حذف"><i class="fa fa-trash-o"></i></button></span>
							</td>
						</tr>

						<div class="example-modal">
							<div class="modal fade" id="delete<?php echo $row["id"]; ?>" tabindex="-1" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">پیام</h4>
										</div>
										<div class="modal-body">
											<p>آیا واقعا می‌خواهید این تراکنش را حذف کنید؟</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" data-dismiss="modal">خیر</button>
											<form data-pjax method="post" action="<?php echo MAIN_URL; ?>admin/transactions" id="trans-del-<?php echo $row["id"]; ?>">
												<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" form="trans-del-<?php echo $row["id"]; ?>">
												<input type="hidden" name="delete" value="1" form="trans-del-<?php echo $row["id"]; ?>">
												<input type="submit" class="btn btn-primary" value="بله" form="trans-del-<?php echo $row["id"]; ?>">
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