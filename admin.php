<? 
include 'tools/dbconnect.php';


	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	session_start(); // Use session variable on this page.

	require_once( 'classes/Users.php');

	$Users = new Users();
	
	if($Users->CheckIfSignedIn() == false) { // if session variable "username" does not exist.
		header('Location: login.php');
		exit;
	}
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
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
	<? 
	include 'menu.php';
	?>
	
	</div>
	
<div class="content" align=center>
<table>
<?
	if($_SESSION['authlevel'] > 0) { // if session variable "username" does not exist.
		echo '<br><a href="adminUsers_ChangePassword.php">Change Password</a><br>';
		echo '<a href="adminUsers.php">Manage Members</a><br>';
		echo 'Testing - Sync from Member DB <br>';
	}
	else
	{
		echo "No settings available.";
	}
	
	?>




</table>





	
</div>




		