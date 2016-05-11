

<?php
session_start();
include("connection.php");
if(isset($_POST['subrank']) and isset($_SESSION['student']))
{
	//echo"rahul";
	$rank=mysql_real_escape_string($_POST['rank']);
	$reg_no=mysql_real_escape_string($_POST['regnomca']);
	
	$check_rank1 = mysql_query("SELECT * FROM student where cpi='0' AND regno='$reg_no' AND bra='zz'");
				$count_rank1 = mysql_num_rows($check_rank1);
				$reg_no_com1 = strtoupper( substr( $reg_no,0,6 ) );

				if($reg_no_com1=="2015CA"){
					if($count_rank1)
					{
	$query= mysql_query("UPDATE student set cpi='$rank' WHERE regno='$reg_no' and bra='zz' ");
	if($query)
	{
		//goto choice
		echo"<script>alert('submitted successfully');</script>";
    echo "<script>location.href='choice';</script>";


	}
	else{
		echo"<script>alert('ERROR plz contact web team immediately!!!');</script>";
    echo "<script>location.href='home';</script>";
	}}
	else
	{
		echo"<script>alert('you have already submitted your rank.');</script>";
    echo "<script>location.href='choice';</script>";
	}
}
	else
	{
		echo"<script>alert('ERROR!!! Note:Only MCA 1st year are allowed to submit their ranks. If you are eligible then contact web team immediately!!!');</script>";
        echo "<script>location.href='home';</script>";
        //error contact web team
	}

}
else
{
	echo"<script>alert('bad session.');</script>";
	echo "<script>location.href='home';</script>";
}
?>