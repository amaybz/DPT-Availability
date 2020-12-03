	
<?


	session_start(); // Use session variable on this page.
	
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	

	include 'tools/dbconnect.php';
	require_once( 'classes/Users.php');
	require_once( 'classes/WOLAPI.php');
	require_once( 'classes/Availability.php');
	
	$Users = new Users();
	$WOLAPI = new WOLAPI();
	
	if($Users->CheckIfSignedIn() == false) { // if session variable "username" does not exist.
			echo "0";
			exit;
	}


	if($_POST["MemberID"]){
		$MemberID = $_POST["MemberID"];
		
		
		if ($MemberID != "")
		{
			echo $WOLAPI->GetMemberBasicDetais($MemberID);		
		}
		else
		{
			echo  "Update Failed, MemberID is invalid.";
		}
		
	}
	else
	{
		echo  "Update Failed, MemberID is Required.";
	}

	
?>