<?php 
include '../main.php';
if( isset($_REQUEST['op']) && $_REQUEST['op']=='attype' ) : 
	$member_id	= (!empty($_POST["member_id"])) ? $_POST["member_id"] : 0;
	$type		= "";
?>

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

<?php elseif( isset($_REQUEST['op']) && $_REQUEST['op']=='atloan_id' ) : ?>

<?php 
$member_id	= $_POST["member_id"];
$loan_id	= "";
?>
<?php if(empty($loan_id)) : ?><option value="" selected="selected">انتخاب کنید</option><?php endif; ?>
<?php
$result = $conn->query("SELECT * FROM sgh_loans WHERE member_id='$member_id' AND status='1'");
while($row = $result->fetch_assoc()) : 
	$installment_amount = $row["amount"] / $row["installment_num"];
?>
<option value="<?php echo $row["id"]; ?>" <?php if($loan_id==$row["id"]) : echo 'selected="selected"'; endif;?>><?php echo $row["create_date"].'- '.number_format($row["amount"]).'- '.$row["installment_num"].' ماهه- '.number_format($installment_amount); ?></option>
<?php endwhile; ?>

<?php endif; ?>