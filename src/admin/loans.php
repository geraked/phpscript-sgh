<?php
$title = 'وام‌ها';
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
	$member_idErr = $amountErr = $installment_numErr = $create_dateErr = $descriptionErr = $alSuc = $alErr = "";
	
	if ($_REQUEST['op']=='add') {
		$member_id = $amount = $installment_num = $create_date = $description = "";
	}
	else {
		$result	= $conn->query("SELECT * FROM sgh_loans WHERE id=".test_input($_REQUEST['id'])." LIMIT 1");
		$row	= $result->fetch_assoc();
		extract($row);
		$amount		 = number_format($amount);
		$create_date = str_replace("-", "/", $create_date);
	}	

	if (isset($_POST["alsubmit"])) {
		if (empty($_POST["member_id"])) {
			$member_idErr = "وام گیرنده را انتخاب کنید";
		}
		else {
			$member_id = test_input($_POST["member_id"]);
		}
		
		if (empty($_POST["amount"])) {
			$amountErr = "مبلغ وام را وارد نمایید (ریال)";
		}
		else {
			$amount = test_input($_POST["amount"]);
			if (!preg_match("/^[1-9][0-9\,]{0,11}$/",$amount)) {
				$amountErr = "مبلغ وارد شده صحیح نیست";
			}
		}
		
		if (empty($_POST["installment_num"])) {
			$installment_numErr = "تعداد اقساط را وارد نمایید";
		}
		else {
			$installment_num = test_input($_POST["installment_num"]);
			if (!preg_match("/^[1-9][0-9]{0,2}$/",$installment_num)) {
				$installment_numErr = "تعداد قسط وارد شده صحیح نیست";
			}
		}

		if (!empty($_POST["create_date"])) {
			$create_date = test_input($_POST["create_date"]);
			if (!preg_match("/^[0-9\/]{10}$/",$create_date)) {
				$create_dateErr = "تاریخ وارد شده صحیح نیست";
			}					
		}

		if (!empty($_POST["description"])) {
			$description = test_input($_POST["description"]);
		}

		if ( empty($member_idErr) && empty($amountErr) && empty($installment_numErr) && empty($create_dateErr) && empty($descriptionErr) ) {
			if ($_REQUEST['op']=='add') {
				$sql = "INSERT INTO sgh_loans (member_id, amount, installment_num, create_date, description) 
				VALUES ('$member_id', '".str_replace(",", "", $amount)."', '$installment_num', '$create_date', '$description')";
			}
			else {
				$sql = "UPDATE sgh_loans SET member_id='$member_id', amount='".str_replace(",", "", $amount)."', installment_num='$installment_num', create_date='$create_date', description='$description' WHERE id=".test_input($_REQUEST['id']);				
			}			
			
			if ($conn->query($sql) === TRUE) {
				$alSuc = ($_REQUEST['op']=='add') ? "وام مورد نظر با موفقیت ایجاد گردید." : "وام مورد نظر با موفقیت ویرایش گردید.";
				if ($_REQUEST['op']=='add') {
					$member_id = $amount = $installment_num = $create_date = $description = "";
				}	
			} 
			else {
				$alErr = ($_REQUEST['op']=='add') ? "مشکلی در ایجاد وام به وجود آمده است." : "مشکلی در ویرایش وام به وجود آمده است.";
			}
		}
	}
	?>	
	
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $title; ?>
			<small><?php if($_REQUEST['op']=='add') : echo "ایجاد وام جدید"; else : echo "ویرایش وام‌ها"; endif; ?></small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<?php if(!empty($alSuc)) : ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-check"></i>پیام!</h4>
			<?php echo $alSuc; ?>
		</div>
		<?php elseif(!empty($alErr)) : ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-ban"></i>پیام!</h4>
			<?php echo $alErr; ?>
		</div>		
		<?php endif; ?>	
		
		<!-- general form elements -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php if($_REQUEST['op']=='add') : echo "فرم ایجاد وام"; else : echo "فرم ویرایش وام"; endif; ?></h3>
			</div><!-- /.box-header -->
			
			<!-- form start -->
			<form data-pjax method="post" action="<?php if($_REQUEST['op']=='add') : echo MAIN_URL.'admin/loans?op=add'; else : echo MAIN_URL.'admin/loans?op=edit&id='.test_input($_REQUEST['id']); endif; ?>" enctype="multipart/form-data" role="form">
				<div class="box-body">
					<div class="form-group <?php if(!empty($member_idErr)) : echo 'has-error'; endif; ?>">
						<label><?php if(!empty($member_idErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$member_idErr; else : echo "* وام گیرنده"; endif; ?></label>
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
					<div class="form-group <?php if(!empty($amountErr)) : echo 'has-error'; endif; ?>">
						<label for="alamount"><?php if(!empty($amountErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$amountErr; else : echo "* مبلغ وام (ریال)"; endif; ?></label>
						<input type="text" class="form-control" onkeyup="javascript:this.value=addComma(this.value);" style="direction:ltr;" name="amount" value="<?php echo $amount; ?>" id="alamount">
						<p class="help-block">ارقام 9-0 مجاز می‌باشد. حداکثر 10 رقم.</p>
					</div>
					<div class="form-group <?php if(!empty($installment_numErr)) : echo 'has-error'; endif; ?>">
						<label for="alinstallment_num"><?php if(!empty($installment_numErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$installment_numErr; else : echo "* تعداد اقساط"; endif; ?></label>
						<input type="text" class="form-control" style="direction:ltr;" name="installment_num" value="<?php echo $installment_num; ?>" id="alinstallment_num">
						<p class="help-block">ارقام 9-0 مجاز می‌باشد. حداکثر 3 رقم.</p>
					</div>
					<div class="form-group <?php if(!empty($create_dateErr)) : echo 'has-error'; endif; ?>">
						<div class="row">
							<div class="col-md-4">
								<label for="alcreate_date"><?php if(!empty($create_dateErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$create_dateErr; else : echo "تاریخ ایجاد"; endif; ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" name="create_date" value="<?php echo $create_date; ?>" id="alcreate_date">
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php if(!empty($descriptionErr)) : echo 'has-error'; endif; ?>">
						<label for="aldescription"><?php if(!empty($descriptionErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$descriptionErr; else : echo "توضیحات"; endif; ?></label>
						<textarea class="form-control" name="description" rows="3" placeholder="توضیحات مورد نظر را وارد نمایید..." id="aldescription"><?php echo $description; ?></textarea>
					</div>                    
				</div><!-- /.box-body -->

				<div class="box-footer">
					<input name="alsubmit" type="hidden" value="1">
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
			<small>لیست وام‌ها</small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<?php 
		$deleteSuc = $deleteErr = "";

		if (isset($_POST["delete"])) {
			$sql = "DELETE FROM sgh_loans WHERE id=".test_input($_POST["id"]);
			if ($conn->query($sql) === TRUE) {
				$deleteSuc = "وام مورد نظر با موفقیت حذف گردید.";
			} else {
				$deleteErr = "مشکلی در حذف وام به وجود آمده است.";
			}			
		}

		
		$statusChangeSuc = $statusChangeErr = "";
		
		if (isset($_POST["statusChange"])) {
			$statusChange = ($_POST["statusChange"]==0) ? 1 : 0;
			$sql		  = "UPDATE sgh_loans SET status='$statusChange' WHERE id=".test_input($_POST["id"]);
			if ($conn->query($sql) === TRUE) {
				$statusChangeSuc = "وضعیت وام مورد نظر، با موفقیت تغییر یافت.";
			} else {
				$statusChangeErr = "مشکلی در تغییر وضعیت وام به وجود آمده است.";
			}			
		}		
		
		
		$member_id = $status = $dstart = $dend = "";
		$sql = "SELECT * FROM sgh_loans";
		
		if (isset($_POST["llsearch"])) {		
			$member_id	= test_input($_POST["member_id"]);
			$status		= test_input($_POST["status"]);
			$dstart		= test_input(str_replace("/", "-", $_POST["dstart"]));
			$dend		= test_input(str_replace("/", "-", $_POST["dend"]));
			
			switch(true) {
				// Part 1
				case ($member_id!="همه" && $status!="همه" && $dstart!="" && $dend!=""):
					$sql = "SELECT * FROM sgh_loans WHERE member_id='$member_id' AND status='$status' AND create_date between '$dstart' and '$dend'";
					break;
				case ($member_id=="همه" && $status!="همه" && $dstart!="" && $dend!=""):
					$sql = "SELECT * FROM sgh_loans WHERE status='$status' AND create_date between '$dstart' and '$dend'";
					break;
				case ($member_id!="همه" && $status=="همه" && $dstart!="" && $dend!=""):
					$sql = "SELECT * FROM sgh_loans WHERE member_id='$member_id' AND create_date between '$dstart' and '$dend'";
					break;
				case ($member_id!="همه" && $status!="همه" && $dstart=="" && $dend!=""):
					$sql = "SELECT * FROM sgh_loans WHERE member_id='$member_id' AND status='$status' AND create_date<='$dend'";
					break;
				case ($member_id!="همه" && $status!="همه" && $dstart!="" && $dend==""):
					$sql = "SELECT * FROM sgh_loans WHERE member_id='$member_id' AND status='$status' AND create_date>='$dstart'";
					break;
				// Part 2
				case ($member_id=="همه" && $status=="همه" && $dstart!="" && $dend!=""):
					$sql = "SELECT * FROM sgh_loans WHERE create_date between '$dstart' and '$dend'";
					break;
				case ($member_id=="همه" && $status!="همه" && $dstart=="" && $dend!=""):
					$sql = "SELECT * FROM sgh_loans WHERE status='$status' AND create_date<='$dend'";
					break;
				case ($member_id=="همه" && $status!="همه" && $dstart!="" && $dend==""):
					$sql = "SELECT * FROM sgh_loans WHERE status='$status' AND create_date>='$dstart'";
					break;
				// Part 3
				case ($member_id!="همه" && $status=="همه" && $dstart=="" && $dend!=""):
					$sql = "SELECT * FROM sgh_loans WHERE member_id='$member_id' AND create_date<='$dend'";
					break;
				case ($member_id!="همه" && $status=="همه" && $dstart!="" && $dend==""):
					$sql = "SELECT * FROM sgh_loans WHERE member_id='$member_id' AND create_date>='$dstart'";
					break;
				// Part 4
				case ($member_id!="همه" && $status!="همه" && $dstart=="" && $dend==""):
					$sql = "SELECT * FROM sgh_loans WHERE member_id='$member_id' AND status='$status'";
					break;
				// Part 5
				case ($member_id!="همه" && $status=="همه" && $dstart=="" && $dend==""):
					$sql = "SELECT * FROM sgh_loans WHERE member_id='$member_id'";
					break;
				case ($member_id=="همه" && $status!="همه" && $dstart=="" && $dend==""):
					$sql = "SELECT * FROM sgh_loans WHERE status='$status'";
					break;
				case ($member_id=="همه" && $status=="همه" && $dstart!="" && $dend==""):
					$sql = "SELECT * FROM sgh_loans WHERE create_date>='$dstart'";
					break;
				case ($member_id=="همه" && $status=="همه" && $dstart=="" && $dend!=""):
					$sql = "SELECT * FROM sgh_loans WHERE create_date<='$dend'";
					break;		
				default:
					$sql = "SELECT * FROM sgh_loans";
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
		
		<?php if(!empty($statusChangeSuc)) : ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-check"></i>پیام!</h4>
			<?php echo $statusChangeSuc; ?>
		</div>
		<?php elseif(!empty($statusChangeErr)) : ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-ban"></i>پیام!</h4>
			<?php echo $statusChangeErr; ?>
		</div>
		<?php endif; ?>			
	
		
		<div class="box box-solid">
			<div class="box-body">
				<form data-pjax method="post" action="<?php echo MAIN_URL.'admin/loans'; ?>" enctype="multipart/form-data" role="form" class="">
					<div class="row">
						<div class="col-md-4 col-sm-6">					
							<div id="llmember_id" class="form-group <?php if(!empty($member_idErr)) : echo 'has-error'; endif; ?>">
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
							<div id="llstatus" class="form-group <?php if(!empty($statusErr)) : echo 'has-error'; endif; ?>">
								<label><?php if(!empty($statusErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$statusErr; else : echo "وضعیت وام"; endif; ?></label>
								<select class="form-control select2" name="status" style="width:100%;">
									<option value="همه" <?php if(empty($status) || $status=="همه") : echo 'selected="selected"'; endif;?>>همه</option>
									<option value="1" <?php if($status=="1") : echo 'selected="selected"'; endif;?>>فعال</option>
									<option value="0" <?php if($status=="0") : echo 'selected="selected"'; endif;?>>غیر فعال</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-9">
							<!-- Date range -->
							<div id="llrange" class="form-group">
								<label>محدوده تاریخ دریافت</label>
								<div class="input-daterange input-group" id="datepicker">
									<input type="text" class="form-control" name="dstart" value="<?php echo str_replace("-", "/", $dstart); ?>">
									<span class="input-group-addon">تا</span>
									<input type="text" class="form-control" name="dend" value="<?php echo str_replace("-", "/", $dend); ?>">
								</div>
							</div>
						</div>
						<div class="col-md-1 col-xs-3">
							<div class="form-group">
								<input type="hidden" name="llsearch" value="1">
								<button type="submit" class="btn btn-default form-control" style="margin-top:25px;"><i class="icon fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</form>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
		
		
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">لیست وام‌ها</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>وام‌گیرنده</th>
							<th>مبلغ وام</th>
							<th>مبلغ قسط</th>
							<th>تعداد اقساط</th>
							<th>پرداخت‌شده</th>
							<th>تاریخ دریافت</th>
							<th>تاریخ اتمام</th>							
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
							<td><?php echo $member["username"].'- '.$member["firstname"].' '.$member["lastname"]; ?></td>
							<td><?php echo number_format($row["amount"]); ?></td>
							<td><?php $installment_amount = $row["amount"] / $row["installment_num"]; echo number_format($installment_amount); ?></td>
							<td><?php echo $row["installment_num"]; ?></td>
							<td>
							<?php 
							$payment_count = $conn->query("SELECT * FROM sgh_transactions WHERE loan_id=".$row["id"]." AND type='پرداخت قسط'");
							echo $payment_count->num_rows;
							?>
							</td>
							<td><?php echo str_replace("-", "/", $row["create_date"]); ?></td>
							<td>
								<?php 
								$date = date_create($row["create_date"]);
								$date = date_add($date, date_interval_create_from_date_string($row["installment_num"]." month"));
								$date = str_replace("-", "/", date_format($date,"Y-m-d"));
								echo $date;								 
								?>
							</td>							
							<td><?php echo $row["description"]; ?></td>
							<td>
								<form data-pjax method="post" action="<?php echo MAIN_URL.'admin/loans'; ?>" style="display:inline;">
									<input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
									<input type="hidden" name="statusChange" value="<?php echo $row["status"]; ?>">
									<button type="submit" class="btn btn-default btn-xs" value="<?php echo $row["status"]; ?>" data-toggle="tooltip" data-placement="top" title="<?php if($row["status"]==0) : echo 'فعال سازی'; else : echo 'غیر فعال کردن'; endif; ?>"><?php if($row["status"]==0) : echo '<i class="icon fa fa-play-circle-o"></i>'; else : echo '<i class="icon fa fa-stop-circle-o"></i>'; endif; ?></button>
								</form>
								<a href="<?php echo MAIN_URL; ?>admin/loans?op=edit&id=<?php echo $row["id"]; ?>" class="btn btn-default btn-xs" role="button" data-toggle="tooltip" data-placement="top" title="ویرایش"><i class="fa fa-edit"></i></a>
								<span data-toggle="modal" data-target="#delete<?php echo $row["id"]; ?>"><button type="button" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="حذف"><i class="fa fa-trash-o"></i></button></span>
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
											<p>آیا واقعا می‌خواهید این وام را حذف کنید؟</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" data-dismiss="modal">خیر</button>
											<form data-pjax method="post" action="<?php echo MAIN_URL.'admin/loans';?>" role="form" id="lone-del-<?php echo $row["id"]; ?>">
												<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" form="lone-del-<?php echo $row["id"]; ?>">
												<input type="hidden" name="delete" value="1" form="lone-del-<?php echo $row["id"]; ?>">
												<input type="submit" class="btn btn-primary" value="بله" form="lone-del-<?php echo $row["id"]; ?>">
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