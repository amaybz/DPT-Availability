	

	
	
	<? 
	echo '<a href="index.php"> Home </a>';
	echo '<a href="collect.php"> Enter Availability </a>';
	echo '<a href="availability.php"> Unit Availability </a>';
	if($Users->CheckIfSignedIn() == false) 
	{ // if session variable "username" does not exist.
		echo '<a href="login.php"> Login</a>';
		
	}
	else
	{
		echo '<a href="admin.php">Settings</a>';
		echo '<a href="login.php">Logout</a>';
	}
	?>