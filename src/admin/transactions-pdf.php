<?php
session_start();
include '../main.php';

if ( (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > EXP_TIME)) || $_SESSION['admin_login'] == false ) {
	header("Location: ".MAIN_URL."admin/logout");
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


$member_id	= (!empty($_REQUEST["member_id"])) ? test_input($_REQUEST["member_id"]) : "همه";
$type		= (!empty($_REQUEST["type"])) ? test_input($_REQUEST["type"]) : "همه";
$dstart		= (!empty($_REQUEST["dstart"])) ? test_input($_REQUEST["dstart"]) : "";
$dend		= (!empty($_REQUEST["dend"])) ? test_input($_REQUEST["dend"]) : "";

switch(true) {
	// Part 1
	case ($member_id!="همه" && $type!="همه" && $dstart!="" && $dend!=""):
		$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND type='$type' AND create_date between '$dstart' and '$dend' ORDER BY create_date";
		break;
	case ($member_id=="همه" && $type!="همه" && $dstart!="" && $dend!=""):
		$sql = "SELECT * FROM sgh_transactions WHERE type='$type' AND create_date between '$dstart' and '$dend' ORDER BY create_date";
		break;
	case ($member_id!="همه" && $type=="همه" && $dstart!="" && $dend!=""):
		$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND create_date between '$dstart' and '$dend' ORDER BY create_date";
		break;
	case ($member_id!="همه" && $type!="همه" && $dstart=="" && $dend!=""):
		$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND type='$type' AND create_date<='$dend' ORDER BY create_date";
		break;
	case ($member_id!="همه" && $type!="همه" && $dstart!="" && $dend==""):
		$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND type='$type' AND create_date>='$dstart' ORDER BY create_date";
		break;
	// Part 2
	case ($member_id=="همه" && $type=="همه" && $dstart!="" && $dend!=""):
		$sql = "SELECT * FROM sgh_transactions WHERE create_date between '$dstart' and '$dend' ORDER BY create_date";
		break;
	case ($member_id=="همه" && $type!="همه" && $dstart=="" && $dend!=""):
		$sql = "SELECT * FROM sgh_transactions WHERE type='$type' AND create_date<='$dend' ORDER BY create_date";
		break;
	case ($member_id=="همه" && $type!="همه" && $dstart!="" && $dend==""):
		$sql = "SELECT * FROM sgh_transactions WHERE type='$type' AND create_date>='$dstart' ORDER BY create_date";
		break;
	// Part 3
	case ($member_id!="همه" && $type=="همه" && $dstart=="" && $dend!=""):
		$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND create_date<='$dend' ORDER BY create_date";
		break;
	case ($member_id!="همه" && $type=="همه" && $dstart!="" && $dend==""):
		$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND create_date>='$dstart' ORDER BY create_date";
		break;
	// Part 4
	case ($member_id!="همه" && $type!="همه" && $dstart=="" && $dend==""):
		$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' AND type='$type' ORDER BY create_date";
		break;
	// Part 5
	case ($member_id!="همه" && $type=="همه" && $dstart=="" && $dend==""):
		$sql = "SELECT * FROM sgh_transactions WHERE member_id='$member_id' ORDER BY create_date";
		break;
	case ($member_id=="همه" && $type!="همه" && $dstart=="" && $dend==""):
		$sql = "SELECT * FROM sgh_transactions WHERE type='$type' ORDER BY create_date";
		break;
	case ($member_id=="همه" && $type=="همه" && $dstart!="" && $dend==""):
		$sql = "SELECT * FROM sgh_transactions WHERE create_date>='$dstart' ORDER BY create_date";
		break;
	case ($member_id=="همه" && $type=="همه" && $dstart=="" && $dend!=""):
		$sql = "SELECT * FROM sgh_transactions WHERE create_date<='$dend' ORDER BY create_date";
		break;		
	default:
		$sql = "SELECT * FROM sgh_transactions ORDER BY create_date";
}

if ($member_id != "همه") {
	$member_result1 = $conn->query("SELECT username, firstname, lastname FROM sgh_members WHERE id=".$member_id." LIMIT 1");
	$member1 = $member_result1->fetch_assoc();
	$t_member = $member1["username"].'- '.$member1["firstname"].' '.$member1["lastname"];
}
else {
	$t_member = $member_id;
}

$html1 = '
<div id="header">
	<div class="col-md-6">
		<p>تراکنش کننده: <b>'.$t_member.'</b></p>
		<p>نوع تراکنش: <b>'.$type.'</b></p>
	</div>
	<div class="col-md-6">
		<p>تاریخ آغاز: <b>'.str_replace("-", "/", $dstart).'</b></p>
		<p>تاریخ پایان: <b>'.str_replace("-", "/", $dend).'</b></p>
	</div>
</div>

<table class="table">
	<thead>
		<tr>
			<th>تاریخ</th>
			<th>تراکنش‌کننده</th>
			<th>نوع</th>
			<th>مبلغ (ریال)</th>
			<th>توضیحات</th>
		</tr>
	</thead>
	<tbody>
';

$html3 = '
	</tbody>
</table>
';


//==============================================================
	// Set Header and Footer
	$h = array (
  'odd' => 
  array (
    'R' => 
    array (
      'content' => jdate('زمان: H:i:s - تاریخ: Y/m/d'),
      'font-size' => 8,
      'font-style' => 'B',
    ),
    'L' => 
    array (
      'content' => SGH_TITLE."- لیست تراکنش‌ها",
      'font-size' => 8,
      'font-style' => 'B',
    ),
    'line' => 1,
  ),
  'even' => 
  array (
    'L' => 
    array (
      'content' => '{PAGENO}',
      'font-size' => 8,
      'font-style' => 'B',
    ),
    'R' => 
    array (
      'content' => "\xd9\x82\xd8\xa7\xd9\x84 \xd8\xa7\xd9\x84\xd8\xb1\xd8\xa6\xd9\x8a\xd8\xb3",
      'font-size' => 8,
      'font-style' => 'B',
    ),
    'line' => 1,
  ),
);

	$f = array (
  'odd' => 
  array (
    'L' => 
    array (
      'content' => '',
      'font-size' => 8,
      'font-style' => 'BI',
    ),
    'C' => 
    array (
      'content' => '- {PAGENO} -',
      'font-size' => 8,
    ),
    'R' => 
    array (
      'content' => "",
      'font-size' => 8,
    ),
    'line' => 0,
  ),
  'even' => 
  array (
    'L' => 
    array (
      'content' => "",
      'font-size' => 8,
      'font-style' => 'B',
    ),
    'C' => 
    array (
      'content' => '- {PAGENO} -',
      'font-size' => 8,
    ),
    'R' => 
    array (
      'content' => '',
      'font-size' => 8,
      'font-style' => 'BI',
    ),
    'line' => 0,
  ),
);
//==============================================================

//==============================================================
require_once '../theme/plugins/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
	'mode' => 'utf-8',
]);
 
$mpdf->SetTitle(SGH_TITLE."- لیست تراکنش‌ها"); 

$mpdf->SetDirectionality('rtl');

$mpdf->autoLangToFont = true;

$mpdf->setHeader($h);
$mpdf->setFooter($f);
$mpdf->defaultPageNumStyle = 'arabic-indic';

$stylesheet = file_get_contents(MAIN_URL.'theme/plugins/mpdf/pdf-style.css');
$mpdf->WriteHTML($stylesheet,1); // The parameter 1 tells that this is css/style only and no body/html/text


$mpdf->WriteHTML($html1);

$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
	$member_result = $conn->query("SELECT username, firstname, lastname FROM sgh_members WHERE id=".$row["member_id"]." LIMIT 1");
	$member = $member_result->fetch_assoc();							
	
	$mpdf->WriteHTML('		
		<tr>
			<td>'.str_replace("-", "/", $row["create_date"]).'</td>
			<td>'.$member["username"].'- '.$member["firstname"].' '.$member["lastname"].'</td>
			<td>'.$row["type"].'</td>
			<td>'.number_format($row["amount"]).'</td>
			<td>'.$row["description"].'</td>			
		</tr>
	');

}

$mpdf->WriteHTML($html3);

$mpdf->Output('SGH-Transactions.pdf', 'I');

exit;
//==============================================================
?>