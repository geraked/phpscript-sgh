<!-- right side column. contains the logo and sidebar -->
<aside class="main-sidebar">

	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-right image">
				<img src="<?php if(empty($admin['pic'])) : echo MAIN_URL.'theme/dist/img/noavatar.png'; else : echo MAIN_URL.$admin['pic']; endif; ?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-right info">
				<p><?php echo $admin['username']; ?></p>
				<!-- Status -->
				<a href="#"><i class="fa fa-circle text-success"></i> آنلاین</a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<ul class="sidebar-menu">
			<li class="header">هدر</li>
			<!-- Optionally, you can add icons to the links -->
			<!--<li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>-->
			<li class="treeview">
				<a href="#"><i class="fa fa-users"></i> <span>اعضا</span> <i class="fa fa-angle-left pull-left"></i></a>
				<ul class="treeview-menu">
					<li><a data-pjax href="<?php echo MAIN_URL;?>admin/members?op=add">افزودن عضو جدید</a></li>
					<li><a data-pjax href="<?php echo MAIN_URL;?>admin/members">لیست و ویرایش اعضا</a></li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#"><i class="fa fa-money"></i> <span>وام‌ها</span> <i class="fa fa-angle-left pull-left"></i></a>
				<ul class="treeview-menu">
					<li><a data-pjax href="<?php echo MAIN_URL;?>admin/loans?op=add">ایجاد وام جدید</a></li>
					<li><a data-pjax href="<?php echo MAIN_URL;?>admin/loans">لیست و ویرایش وام‌ها</a></li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#"><i class="fa fa-credit-card"></i> <span>تراکنش‌ها</span> <i class="fa fa-angle-left pull-left"></i></a>
				<ul class="treeview-menu">
					<li><a data-pjax href="<?php echo MAIN_URL;?>admin/transactions?op=add">ثبت تراکنش جدید</a></li>
					<li><a data-pjax href="<?php echo MAIN_URL;?>admin/transactions">لیست و ویرایش تراکنش‌ها</a></li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#"><i class="fa fa-user"></i> <span>مدیران</span> <i class="fa fa-angle-left pull-left"></i></a>
				<ul class="treeview-menu">
					<li><a data-pjax href="<?php echo MAIN_URL;?>admin/admins?op=add">افزودن مدیر جدید</a></li>
					<li><a data-pjax href="<?php echo MAIN_URL;?>admin/admins">لیست و ویرایش مدیران</a></li>
				</ul>
			</li>			
		</ul><!-- /.sidebar-menu -->
	</section>
	<!-- /.sidebar -->
</aside>