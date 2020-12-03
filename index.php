<?php

	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	session_start(); // Use session variable on this page.

	include 'tools/dbconnect.php';
	require_once( 'classes/Availability.php');
	require_once( 'classes/Users.php');
	require_once( 'classes/WOLAPI.php');

	$Users = new Users();
	$WOLAPI = new WOLAPI();
	$Availability = new Availability();
	
	if($Users->CheckIfSignedIn() == false) { // if session variable "username" does not exist.
		header('Location: login.php');
		exit;
	}
	
	if($_GET["ShowAvail"]){
		$ShowAvail = $_GET["ShowAvail"];
	}
	

?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>DPT SES</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico">
</head>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<div class="head">
	<img src="logo.2b1db366.svg" alt="SES Logo" width="20" height="20">
	Dapto Availability
</div>


	
	<div class="menuInline" align="center">
	<?php 	
		include 'menu.php';
	?>
		 
	</div>
	
<div class="content" align=center>
	<p >
	
	<? 
	//$member = $WOLAPI->getMemberByNumber("40040522"); 
	//echo json_encode($member, true);
	
	?>
	
	
	<form id="frmAvailability"> 
		<select class="Selectheading" name='ShowAvail' id='ShowAvail'  onchange='document.getElementById("frmAvailability").submit();'>
			<option value="1">Available Members</option>
			<?php 
			if($ShowAvail==2)
			{
				echo '<option selected value="2">All Members</option>';
			}
			else
			{
				echo '<option value="2">All Members</option>';
			}
			?>
			
			
		</select>
	</form>
	
	<?
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	?>
	

	
		<h2>Alpha Team</h2>
		
		<?php

			//echo date();
			
			

			$sql = "SELECT * FROM tblmembers where IDTeam=1  order by Name";	
			$result = $db->query($sql);
			$RowCount = $result->num_rows;
			echo '<table class="memberstable">';
			if ($RowCount > 0)
			{
				$MemberCount = 0;
				
				while($row = $result->fetch_assoc())
				{
					
					if($Availability->CheckIfAvailable($row["MemberID"]))
					{
						//$member = $WOLAPI->getMemberByNumber($row["MemberID"]); 
						$WOLAPI->ShowMembersDetails($row["MemberID"]);
						//echo $WOLAPI->GetMemberBasicDetais($row["MemberID"]);
						$MemberCount++;
					}
					else if($ShowAvail==2)
					{
						$WOLAPI->ShowMembersDetails($row["MemberID"]);
						$MemberCount++;
					}
				}
				if($MemberCount == 0)
				{
					echo "<tr><td>No Available Members</td></tr>";
				}
				
			}
			else
			{
				echo "No Members";
			}
			echo "</table>";
			
				
		?>
	</p>
	
	<div id="loadinga">LOADING...</div>
	
	<?
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	?>
	<script>
		$('#loadinga').hide();
	</script>
	
		<h2>Bravo Team </h2>
		
		<?

			//echo date();

			$sql = "SELECT * FROM tblmembers where IDTeam=2  order by Name";	
			$result = $db->query($sql);
			$RowCount = $result->num_rows;
			echo '<table class="memberstable">';
			if ($RowCount > 0)
			{
				$MemberCount = 0;
				
				while($row = $result->fetch_assoc())
				{
					
					if($Availability->CheckIfAvailable($row["MemberID"]))
					{
						$WOLAPI->ShowMembersDetails($row["MemberID"]);
						$MemberCount++;
					}
					else if($ShowAvail==2)
					{
						$WOLAPI->ShowMembersDetails($row["MemberID"]);
						$MemberCount++;
					}
				}
				if($MemberCount == 0)
				{
					echo "<tr><td>No Available Members</td></tr>";
				}
				
			}
			else
			{
				echo "No Members";
			}
			echo "</table>";
			
		?>
	</p>
	
	<div id="loadingb">LOADING...</div>
	
	<?
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	?>
	<script>
		$('#loadingb').hide();
	</script>
	
	<p>
		<h2>All Other Teams</h2>
		
		<?

			//echo date();

			$sql = "SELECT * FROM tblmembers where IDTeam=3 or IDTeam=5 or IDTeam=6  order by Name";	
			$result = $db->query($sql);
			$RowCount = $result->num_rows;
			echo '<table class="memberstable">';
			if ($RowCount > 0)
			{
				$MemberCount = 0;
				
				while($row = $result->fetch_assoc())
				{
					
					if($Availability->CheckIfAvailable($row["MemberID"]))
					{
						$WOLAPI->ShowMembersDetails($row["MemberID"]);
						$MemberCount++;
					}
					else if($ShowAvail==2)
					{
						$WOLAPI->ShowMembersDetails($row["MemberID"]);
						$MemberCount++;
					}
				}
				
				if($MemberCount == 0)
				{
					echo "<tr><td>No Available Members</td></tr>";
				}
				
			}
			else
			{
				echo "No Members";
			}
			echo "</table>";
			
		
		?>
	</p>
	<?
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	?>
	
		<p>
		<h2>Charlie Team </h2>
		
		<?

			//echo date();

			$sql = "SELECT * FROM tblmembers where IDTeam=4  order by Name";	
			$result = $db->query($sql);
			$RowCount = $result->num_rows;
			echo '<table class="memberstable">';
			if ($RowCount > 0)
			{
				$MemberCount = 0;
				
				while($row = $result->fetch_assoc())
				{
					
					if($Availability->CheckIfAvailable($row["MemberID"]))
					{
						$WOLAPI->ShowMembersDetails($row["MemberID"]);
						$MemberCount++;
					}
				}
				if($MemberCount == 0)
				{
					echo "<tr><td>No Available Members</td></tr>";
				}
				
			}
			else
			{
				echo "No Members";
			}
			echo "</table>";
			
			ob_flush();
		?>
	</p>
	
	
	
	
	
	
</div>







		