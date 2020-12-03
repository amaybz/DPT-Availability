<?

	//Created by Aiden Mayberry

	//uncomment the below 2 lines for debugging.
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	
	
	session_start(); // Use session variable on this page.

	include 'tools/dbconnect.php';
	require_once( 'classes/Users.php');

	$Users = new Users();
	
	if($Users->CheckIfSignedIn() == false) { // if session variable "username" does not exist.
		$Users->ShowAccessDenied();
		exit;
	}
	
	
	$userid = $_SESSION['userid'];
	//get user Details
	$sql = "SELECT * FROM webusers where username='$userid'" ;
	$result = $db->query($sql);
	$RowCount = $result->num_rows;
	
	if ($RowCount > 0)
	{
		$row = $result->fetch_assoc();
		$name = $row['name'];
	}
	else
	{
		echo "no record found";
		exit;
	}
	
	
	if($_POST["UpdatePass"])
	{
	
		$userid=$_SESSION['userid'];
		$name=$_SESSION['username'];
		$CurrentPassword =$_POST['CurrentPassword']; 
		$password1 = $_POST['password1']; 
		$password2 = $_POST['password2']; 
		
		if($Users->CheckPassword($userid, $CurrentPassword) == 1)
		{
			if($password1 != '' and $password2 != '' and $password1 == $password2)
			{
				$Updatepass = $Users->UpdatePassword($userid, $password1, $password2);
				if($Updatepass == 1)
				{
					$message="Password Updated";
				}
			}
			else
			{
				$message="Please fill in all details";
			}
				
		}
		else
		{ 
			$message="--- Incorrect Username or Password ---";
		}
		
		
	
	}
		
		


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>DPT SES - Change Password</title>
<link href="style.css"rel="stylesheet" type="text/css" />
</head>


<body>

<h2 class="head">Change Password</h2>



<div class="content">

	<? echo "<p>" . $message . "</p>"; ?>
	<div align="center">
		<table><tr><td><b>Current Password</b></td></tr>
		<? 
			echo '<form id="FrmChangePassword" name="FrmChangePassword" method="post"action="adminUsers_ChangePassword.php"/>'; 

			echo '<tr><td><input name="CurrentPassword" type="password" Value="" id="CurrentPassword" size="25" maxlength="24"/></td>'; 
			echo '<tr><td><b>Password</b></td><td><b>Comfirm</b></td></tr>';
			echo '<tr><td><input name="password1" type="password" Value="" id="password1" size="25" maxlength="24"/></td>'; 
			echo '<td><input name="password2" type="password" Value="" id="password2" size="25" maxlength="24"/></td></tr>'; 

	
		
		?>
		</table>
		<? echo '<p align="center"><input type="submit" value="save" name="UpdatePass" id="UpdatePass"></p>'; ?>
		
		</form>
	</div>
	<br>

	</p>
	</div>

	<div class="menu">
		<p><a href="index.php">Back</a></p>
	</div>
</body>
</html>

