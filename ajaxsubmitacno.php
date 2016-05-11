<?php
error_reporting(0);
include("connection.php");
//Fetching Values from URL
$user_regno	=$_POST['reg_no1'];
$user_bookno=$_POST['book_no1'];
$user_accno	=$_POST['accno1'];
//Insert query

$check = mysql_query("SELECT * FROM allotment_details WHERE acc_no='$user_accno' AND updated='yes'");
	$count=mysql_num_rows($check);
	if($count)
	{
		$output = json_encode(array('<b>Duplicate ACCNO. Please try different one.</b>'));
		echo $output;
	}
	else
	{
		$query = mysql_query("update allotment_details set acc_no='$user_accno', updated='yes' where reg_no='$user_regno' and book_no='$user_bookno'");
 

if(!$query)
	{
		
		$output = json_encode(array('<b>Unable to add! Please try after some time or contact admin.</b>'));
		echo $output;
	}else{
		$output = json_encode(array('type'=>'message', 'text' => 'accno added successfully<script>window.location.reload();</script>'));
		echo $output;
	}
	}
	
?>
