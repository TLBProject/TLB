<?php
error_reporting(0);
session_start();
if (!(isset($_SESSION['admin']) || isset($_SESSION['staff']) )) {
	header("location: home");
}
header('Content-type: text/html');
set_time_limit(0);
//http_response_code(200);
error_reporting(0);
session_start();

include_once('class.tlbConfig.php');
$config = new tlbConfig();
$base = $config->baseServer;
include_once($_SERVER['DOCUMENT_ROOT'].$base.'class.pagecontent.php');
include_once($_SERVER['DOCUMENT_ROOT'].$base.'class.misc.php');
$misc = new misc();
$pgcontent = new pagecontent();

$semester = $misc->getSemester();
$session = $misc->getSession();
if(isset($_SESSION['student'])){
	$Student = $misc->getInfo($_SESSION['student'],'student');
	$Student['course'] = $misc->getCourse($_SESSION['student']);
	$Student['branch'] = $misc->getBranch($_SESSION['student']);
}
elseif(isset($_SESSION['admin']))
	$Admin = $misc->getInfo($_SESSION['admin'],'admin');
elseif(isset($_SESSION['staff']))
	$Staff = $misc->getInfo($_SESSION['staff'],'staff');
	
$path = basename($_SERVER['REQUEST_URI'],".php");
$page = strtok($path,'?');
$pages = array('home','instruction','cancelreg','books','choice','notice','contact','password','feecollect','reprint','feecancel','stats','bookedit','allot','print','tokenlist','tokens');
$page = (in_array($page,$pages))? $page : 'home';
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Aayushee Aggrawal">
<title>TextBook Lending Bank | Central Library | MNNIT Allahabad</title>
<link rel="icon" type="image/ico" href="<?php echo $base; ?>images/tb.png"/>
<script src="<?php echo $base; ?>scripts/jquery-min.js" type="text/javascript"></script>
<script src="<?php echo $base; ?>scripts/jquery.sortable.min.js" type="text/javascript"></script>
<script src="<?php echo $base; ?>scripts/default.js" type="text/javascript"></script>
<link href="<?php echo $base; ?>styles/default.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div id="header">
	<h1><a href="#">TLB Portal for <?php echo $semester; ?> Semester <?php echo $session; ?></a></h1>
	<?php $misc->add_navmenu(); ?>
<div id="headerbg"></div>
</div>
<div id="page">
	<div id="content">
		<div id="title"><h2>Students Allotment Details</h2>
		<span class="welcome">Welcome 
		<?php if(isset($Student)) echo $Student['name'];
			elseif(isset($Admin)) echo $Admin['name'];
			elseif(isset($Staff)) echo $Staff['name'];
			else echo 'Guest'; ?>
		</span></div>
		<div class="body" align="center">

<!--#################################################################################################-->

<?php

include("connection.php");

				if (isset($_GET['issue'])) {
					$reg_no = mysql_real_escape_string($_GET['reg_no']);
					$stuquery = mysql_query("SELECT * FROM student WHERE regno = '$reg_no'");
					$count = mysql_num_rows($stuquery);
					if (!$count) {
						echo"<script>alert('Incorrect registration number!!!')</script>";
						echo "<script>window.location = 'allot';</script>";
					}
					$sturow = mysql_fetch_assoc($stuquery);
					$name = $sturow['name'];
					$bra = $sturow['bra'];
					$prog = $sturow['prog'];
					if ($prog == "bt" ) {
						$prog = "BTech";
					}
					if ($prog == "mc" ) {
						$prog = "MCA";
					}
					?>
					<div style="text-align:center;">
					<p><b>Name:</b> <?php echo $name; ?>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; <b>Programme:</b> <?php echo $prog; ?></p>
					<p><b>Reg. No.:</b> <?php echo $reg_no; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Branch:</b> <?php echo $bra; ?></p>
					</div>
						

					<?php
					$query = mysql_query("SELECT * FROM allotment_details WHERE reg_no = '$reg_no'");
					$countuser = mysql_num_rows($query);
					if (!$countuser) {
						
						echo "<h2>Not Applicable</h2>";
					}else{
					?>
					<table border>
						<tr><td><b>S.No.</b></td><td><b>Books alloted</b></td><td><b>Author</b></td><td><b>Acc. no.</b></td>
						<?php if (isset($_GET['finalissue'])) { ?>
							<td><b>Issued</b></td>
							<?php } ?>
							<?php if (isset($_GET['return'])) { ?>
							<td><b>Returned</b></td>
							<?php } ?>
							
						</tr>
						<?php
					$i=1;
					$flag_update = "no";
					$flag_issue = "no";
					while($row = mysql_fetch_array($query)){

						$reg_no = $row['reg_no'];
						$reg_no_com1 = strtoupper( substr( $reg_no,4,2 ) );

				        if($reg_no_com1=="CA")
						{
							$type='mc';
						}
						else
						{
							$type='bt';
						}
						$book_no = $row['book_no'];
						
						$book_query = mysql_query("SELECT * FROM books_master WHERE book_no = '$book_no' AND type='$type'");
						$bookrow = mysql_fetch_assoc($book_query);
						$bookname = $bookrow['title'];
						$bookauthor = $bookrow['author'];
						$acc_no = $row['acc_no'];
						$update = $row['updated'];
						if ($update == "yes") {
							$flag_update = "yes";
						}
						else{
							$flag_update = "no";
						}
						$issued = $row['issued'];
						if ($issued == "yes") {
							$flag_issue = "yes";
						}
						else{
							$flag_issue = "no";
						}
						$returned = $row['returned'];
						?>
						<div id="result"></div>
						<tr id="<?php echo $i; ?>">
						<td><?php echo $i; ?>
						<input type="hidden" id="regno<?php echo $i; ?>" value="<?php echo $reg_no ?>">
					</td>
						<td><?php echo $bookname; ?>
						<input type="hidden" id="bookno<?php echo $i; ?>" value="<?php echo $book_no?>">
					</td>
						<td><?php echo $bookauthor; ?></td>
						<td><?php
							if($update=="yes"){
								
								?>
								<div id="accnodetail<?php echo $i; ?>"><b>Fetching detail...</b></div>
								<a href="editaccno?reg_no=<?php echo $reg_no; ?>&editbookno=<?php echo $book_no; ?>"><input type="button" value="edit" ></a>

								<script>
								//fetching accno from database using ajax
								function showDetail() {
								    
								        if (window.XMLHttpRequest) {
								            // code for IE7+, Firefox, Chrome, Opera, Safari
								            xmlhttp = new XMLHttpRequest();
								        } else {
								            // code for IE6, IE5
								            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
								        }
								        xmlhttp.onreadystatechange = function() {
								            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
								                document.getElementById("accnodetail<?php echo $i; ?>").innerHTML = xmlhttp.responseText;
								            }
								        }
								        xmlhttp.open("GET","ajaxgetaccnodetail?regno=<?php echo $reg_no; ?>&bookno=<?php echo $book_no; ?>",false);
								        xmlhttp.send();
								    }
								
								showDetail();
								</script>

								<?php
						}else{
							if($update == "no")
						
							{ ?>
						

						<input type="text" id="accno<?php echo $i; ?>" placeholder="add accno. here">
						<input id="submit_btn<?php echo $i; ?>" type="button" name="update_details" value="Update">
						
					<!--accno submit using ajax-->
										
					
					
					<script type="text/javascript">
					
					$(document).ready(function(){
					$("#submit_btn<?php echo $i; ?>").click(function(){
					var reg_no = $("#regno<?php echo $i; ?>").val();
					var book_no = $("#bookno<?php echo $i; ?>").val();
					var accno =$("#accno<?php echo $i; ?>").val();
					
					// Returns successful data submission message when the entered information is stored in database.
					var dataString = 'reg_no1='+ reg_no + '&book_no1='+ book_no + '&accno1='+ accno;
					if(reg_no==''||book_no==''||accno=='')
					{
					alert("Please Fill ACCNO Field");
					}
					else
					{
					// AJAX Code To Submit Form.
					$.ajax({
					type: "POST",
					url: "ajaxsubmitacno",
					data: dataString,
					cache: false,
					dataType: "JSON",
								success: function (jsonStr) {
					
									$("#result").html(JSON.stringify(jsonStr));
								}
					//success: function(result){
					//alert(result);
					
					//}
					});
					}
					return false;
					});
					
					});
					
					</script>

					<!--end of ajax submit-->
					<?php
					}}
					 ?>

						</td>
						<?php if (isset($_GET['finalissue']) && $update=="yes") { ?>
						<td>
							<?php
							if ($issued == "no") {
								?>
								<a href="edit_issue_return?reg_no=<?php echo $reg_no; ?>&issue=issue+book&getbookno=<?php echo $book_no; ?>&issued=yes"><input type="button" value="issue"></a>

								

								</script>
								<?php
							}
							if ($issued == "yes") {
								?>
								<img src="images/check.png" height="30" width="30" alt="issued">
								<a href="edit_issue_return?reg_no=<?php echo $reg_no; ?>&issue=issue+book&getbookno=<?php echo $book_no; ?>&issued=no&returned=no"><input type="button" value="edit"></a>
								<?php
							}
							?>
						</td>
						<?php } 
						else{
							if (isset($_GET['finalissue'])){
							echo "<td>Plz update accno</td>";
						}
						}
						?>
						<?php if (isset($_GET['return']) && $update=="yes" && $issued == "yes") { ?>
						<td>
							<?php
							if ($returned == "no") {
								?>
								<a href="edit_issue_return?reg_no=<?php echo $reg_no; ?>&issue=issue+book&getbookno=<?php echo $book_no; ?>&returned=yes"><input type="button" value="return"></a>
								
								<?php
							}
							if ($returned == "yes") {
								?>
								<img src="images/check.png" height="30" width="30" alt="returned">
								<a href="edit_issue_return?reg_no=<?php echo $reg_no; ?>&issue=issue+book&getbookno=<?php echo $book_no; ?>&returned=no"><input type="button" value="edit"></a>
								<?php
							}
							?>
						</td>
						<?php } 
						else{
							if (isset($_GET['return']) && $issued == "no"){
								
							echo "<td>Plz issue book first.</td>";
						}}
						?>
						</tr>
						
					<?php $i++; } ?>
					</table>
					<?php 
					if ($flag_update == "no") {
						echo"All the fields has not been updated yet !!!";
						?>
						
						<?php
					}
					}
				}
				?>


				

<!--####################################################################################################-->				
				</div>
	</div>
	<?php $misc->add_sidemenu($page); ?>
	<ul id="side_menu">
		<li><a href="">Navigation</a>
			<ul style="display:block">
				<li><a href="allotment_details?reg_no=<?php echo $reg_no; ?>&issue=issue+book">Update ACCNO.</a></li>
				<li><a href="allotment_details?reg_no=<?php echo $reg_no; ?>&issue=issue+book&finalissue=yes">Issue</a></li>
				<li><a href="allotment_details?reg_no=<?php echo $reg_no; ?>&issue=issue+book&return=yes">Return</a></li>
				<li><a href="allotment_details?reg_no=<?php echo $reg_no; ?>&issue=issue+book&finalissue=yes&return=yes">Overall</a></li>
				<li><a href="allot">New Student</a></li>

			</ul>
		</li>
		</ul>

	<div style="clear:both"></div>
</div>

<div id="footer" style="position:relative">
<p id="legal">&copy;2014 Central Library, MNNIT. All Rights Reserved. Designed by <a href="<?php echo $base; ?>contact">Webteam TLB</a></p>
	<p id="links"><a href="#">Privacy</a> | <a href="#">Terms</a> | 
	<a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional"><abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a> |
	<a href="http://jigsaw.w3.org/css-validator/check/referer" title="This page validates as CSS"><abbr title="Cascading Style Sheets">CSS</abbr></a></p>
</div>

</body>
</html>
