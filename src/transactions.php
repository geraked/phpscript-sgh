<?php
$title = 'تراکنش‌ها';
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
			<small>لیست تراکنش‌های شما</small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<?php 
		$type = $dstart = $dend = "";
		$sql = "SELECT * FROM sgh_transactions WHERE member_id=".$user['id'];
		
		if (isset($_POST["ltsearch"])) {		
			$member_id	= $user['id'];
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
					$sql = "SELECT * FROM sgh_transactions WHERE member_id=".$user['id'];
			}
		}
		?>				
		
		<div class="box box-solid">
			<div class="box-body">
				<form data-pjax method="post" action="<?php echo MAIN_URL; ?>transactions" enctype="multipart/form-data" role="form">
					<div class="row">
						<div class="col-md-7">
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
						<div class="col-md-4 col-xs-9">
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
				<h3 class="box-title">لیست تراکنش‌ها</h3>
				<a href="<?php echo MAIN_URL;?>transactions-pdf?<?php echo 'member_id='.$user['id'].'&type='.$type.'&dstart='.$dstart.'&dend='.$dend; ?>" class="btn btn-default pull-left pdf-link" role="button" target="_blank" data-toggle="tooltip" data-placement="right" title="دریافت فایل PDF"><i class="icon fa fa-file-pdf-o"></i></a>
			</div><!-- /.box-header -->
			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>تاریخ</th>
							<th>نوع</th>
							<th>مبلغ (ریال)</th>
							<th>توضیحات</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
						$result = $conn->query($sql);
						while($row = $result->fetch_assoc()) :
						?>
						
						<tr>
							<td><?php echo str_replace("-", "/", $row["create_date"]); ?></td>
							<td><?php echo $row["type"]; ?></td>
							<td><?php echo number_format($row["amount"]); ?></td>
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