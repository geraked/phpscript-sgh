<?php
// Test inputs
function test_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

include 'theme/plugins/jdf/jdf.php';

function sgh_date($show = "زمان: H:i:s - تاریخ: Y/m/d")
{
	$timezone = 0; //برای 3:30 عدد 12600 و برای 4:30 عدد 16200 را تنظیم کنید
	$now = date("Y-m-d", time() + $timezone);
	$time = date("H:i:s", time() + $timezone);
	list($year, $month, $day) = explode('-', $now);
	list($hour, $minute, $second) = explode(':', $time);
	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
	$jalali_date = jdate($show, $timestamp);
	return $jalali_date;
}
