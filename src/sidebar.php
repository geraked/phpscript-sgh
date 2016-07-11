<!-- right side column. contains the logo and sidebar -->
<aside class="main-sidebar">

	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-right image">
				<img src="<?php if(empty($user['pic'])) : echo MAIN_URL.'theme/dist/img/noavatar.png'; else : echo MAIN_URL.$user['pic']; endif; ?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-right info">
				<p><?php echo $user['username']; ?></p>
				<!-- Status -->
				<a href="#"><i class="fa fa-circle text-success"></i> آنلاین</a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<ul class="sidebar-menu">
			<li class="header">هدر</li>
			<!-- Optionally, you can add icons to the links -->
			<li><a data-pjax href="<?php echo MAIN_URL;?>loans"><i class="fa fa-money"></i> <span>وام‌ها</span></a></li>
			<li><a data-pjax href="<?php echo MAIN_URL;?>transactions"><i class="fa fa-credit-card"></i> <span>تراکنش‌ها</span></a></li>
			<li><a data-pjax href="<?php echo MAIN_URL;?>profile"><i class="fa fa-edit"></i> <span>ویرایش مشخصات</span></a></li>
		</ul><!-- /.sidebar-menu -->
	</section>
	<!-- /.sidebar -->
</aside>