<?php
error_reporting(0);
							$rgno = mysql_real_escape_string($_GET['reg_no']);
							$bkno = mysql_real_escape_string($_GET['editbookno']);
								include("connection.php");
								$query = mysql_query("update allotment_details set updated='no' where reg_no='$rgno' and book_no='$bkno'");
								if ($query) {
									echo "<script>window.location = 'allotment_details?reg_no=$rgno&issue=issue+book';</script>";

								}
								?>