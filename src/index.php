<?php
$title = 'پیشخوان';
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
			پنل کاربری
			<small>پیشخوان</small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">موجودی شما (پاره‌سهم)</span>
						<span class="info-box-number">
							<?php 
							$result = $conn->query("SELECT SUM(amount) AS psp_sum FROM sgh_transactions WHERE (type='پرداخت پاره‌سهم' OR type='سایر (پرداخت)') AND member_id=".$user['id']);		
							$row = $result->fetch_assoc();
							$psp_sum = $row['psp_sum'];
							
							$result = $conn->query("SELECT SUM(amount) AS sd_sum FROM sgh_transactions WHERE type='سایر (دریافت)' AND member_id=".$user['id']);		
							$row = $result->fetch_assoc();
							$sd_sum = $row['sd_sum'];
							
							echo number_format($psp_sum - $sd_sum).' ریال';
							?>
						</span>
					</div><!-- /.info-box-content -->
				</div><!-- /.info-box -->
			</div><!-- /.col -->
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">بدهی</span>
						<span class="info-box-number">
							<?php 
							$result = $conn->query("SELECT SUM(amount) AS pq_sum FROM sgh_transactions WHERE type='پرداخت قسط' AND member_id=".$user['id']);		
							$row = $result->fetch_assoc(); 
							$pq_sum = $row['pq_sum'];
							
							$result = $conn->query("SELECT SUM(amount) AS dv_sum FROM sgh_loans WHERE member_id=".$user['id']);		
							$row = $result->fetch_assoc();
							$dv_sum = $row['dv_sum'];
							
							echo number_format($dv_sum - $pq_sum).' ریال';
							?>						
						</span>
					</div><!-- /.info-box-content -->
				</div><!-- /.info-box -->
			</div><!-- /.col -->
		</div><!-- /.row -->		
	</section><!-- /.content -->
	
	</div><!-- /#pjax-container -->

</div><!-- /.content-wrapper -->
<?php include 'footer.php'; ?>