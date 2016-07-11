<?php
$title = 'وام‌ها';
include 'header.php';
include 'sidebar.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<div id="loading"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>

	<div data-pjax id="pjax-container">

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $title; ?>
			<small>لیست وام‌های شما</small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<?php 
		$status = $dstart = $dend = "";
		$sql = "SELECT * FROM sgh_loans WHERE member_id=".$user['id'];
		
		if (isset($_POST["llsearch"])) {		
			$member_id	= $user['id'];
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
					$sql = "SELECT * FROM sgh_loans WHERE member_id=".$user['id'];
			}
		}		
		?>			
		
		<div class="box box-solid">
			<div class="box-body">
				<form data-pjax method="post" action="<?php echo MAIN_URL; ?>loans" enctype="multipart/form-data" role="form" class="">
					<div class="row">
						<div class="col-md-7">
							<div id="llstatus" class="form-group <?php if(!empty($statusErr)) : echo 'has-error'; endif; ?>">
								<label><?php if(!empty($statusErr)) : echo '<i class="fa fa-times-circle-o"></i> '.$statusErr; else : echo "وضعیت وام"; endif; ?></label>
								<select class="form-control select2" name="status" style="width:100%;">
									<option value="همه" <?php if(empty($status) || $status=="همه") : echo 'selected="selected"'; endif;?>>همه</option>
									<option value="1" <?php if($status=="1") : echo 'selected="selected"'; endif;?>>فعال</option>
									<option value="0" <?php if($status=="0") : echo 'selected="selected"'; endif;?>>غیر فعال</option>
								</select>
							</div>
						</div>
						<div class="col-md-4 col-xs-9">
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
							<th>مبلغ وام</th>
							<th>مبلغ قسط</th>
							<th>تعداد اقساط</th>
							<th>پرداخت‌شده</th>
							<th>تاریخ دریافت</th>
							<th>تاریخ اتمام</th>
							<th>توضیحات</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
						$result = $conn->query($sql);
						while($row = $result->fetch_assoc()) :
						?>
						
						<tr>
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
						</tr>
				
						<?php endwhile;	?>					  
					</tbody>
				</table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->		

	</section><!-- /.content -->
	
	</div><!-- /#pjax-container -->
</div><!-- /.content-wrapper -->
<?php include 'footer.php'; ?>