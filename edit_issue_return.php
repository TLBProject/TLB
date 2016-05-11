<?php
error_reporting(0);
							$rgno = mysql_real_escape_string($_GET['reg_no']);
							$bkno = mysql_real_escape_string($_GET['getbookno']);


							if (isset($_GET['issued'])) {
								
							
							$issued = mysql_real_escape_string($_GET['issued']);
								include("connection.php");
								$query = mysql_query("update allotment_details set issued='$issued' where reg_no='$rgno' and book_no='$bkno' and updated='yes'");
								if ($query) {
									echo "<script>window.location = 'allotment_details?reg_no=$rgno&issue=issue+book&finalissue=yes';</script>";

								}}
								if (isset($_GET['returned'])) {
								
							
							$returned = mysql_real_escape_string($_GET['returned']);
								include("connection.php");
								$query = mysql_query("update allotment_details set returned='$returned' where reg_no='$rgno' and book_no='$bkno' and updated='yes'");
								if ($query) {
									echo "<script>window.location = 'allotment_details?reg_no=$rgno&issue=issue+book&return=yes';</script>";

								}}

								?>