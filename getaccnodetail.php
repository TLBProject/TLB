<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
error_reporting(0);
$regno = intval($_GET['regno']);
$bookno = intval($_GET['bookno']);

include("connection.php");


$sql="SELECT * FROM allotment_details WHERE reg_no = '$regno' AND book_no = '$bookno' ";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$acc_no = $row['acc_no'];

echo $acc_no;

?>
</body>
</html>