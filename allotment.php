<?php

include("connection.php");

if(isset($_SESSION['admin']) or isset($_SESSION['staff'])){
/*
			echo 'Processing....';
			$misc->allot();
			$misc->generatetoken();
			echo 'Done';
*/
					
		/*	echo "updating books allotment details...";	
				$choicequery = mysql_query("SELECT * FROM choice WHERE alloted='y'");
				while($row=mysql_fetch_array($choicequery)){
					$regno = $row['regno'];
					$book_no = $row['book_no'];			
					
					$insertquery = mysql_query("INSERT INTO allotment_details (reg_no, book_no,acc_no,updated,issued,returned) VALUES ('$regno','$book_no','','no','no','no')");
									

				}
				echo "done";
				*/
				?>
				<div style="text-align:center;">
				<h1>Books allotment details</h1>
				<form method="GET" action="allotment_details">
				<input type="text" name="reg_no" placeholder="enter registration no here..." maxlength="8" required>
				<br><br>
				<input type="submit" name="issue" value="issue/return book">
				
				</form>
			</div>
				<?php
			}
			else
				$page = 'home';
			?>