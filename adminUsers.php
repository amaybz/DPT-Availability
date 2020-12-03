<? 
include 'tools/dbconnect.php';


	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	session_start(); // Use session variable on this page.

	require_once( 'classes/Users.php');
	require_once( 'classes/Availability.php');
	require_once( 'classes/WOLAPI.php');
	
	$Availability = new Availability();
	$Users = new Users();
	$WOLAPI = new WOLAPI();
	
	if($Users->CheckIfSignedIn() == false) { // if session variable "username" does not exist.
		header('Location: login.php');
		exit;
	}
	
	if($_SESSION['authlevel'] < 2) { // if session variable "username" does not exist.
		$Users->ShowAccessDenied();
		exit;
	}
	

	$AdminUser = 1;
	
	$SyncMemberID=$_POST['SyncMemberID'];
	if($SyncMemberID > 1){
		$WOLAPI->SyncMemberDetails($SyncMemberID);
		//echo "synced";
	}
	
	$MemberID=$_POST['MemberID'];
	if($MemberID > 1){
			$Name=$_POST['Name'];
			$Phone=$_POST['Phone'];
			$Team=$_POST['Team'];
			$Availability->AddMember($MemberID,$Name,$Phone,$Team);
	}
	else{
		$MemberID = 1;
	}
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.5; user-scalable=1;" />
<title>DPT SES</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico">
</head>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<div class="head">
	<img src="logo.2b1db366.svg" alt="SES Logo" width="20" height="20">
	Dapto Availability - Members
</div>

<div class="menuInline" align="center">
	<? 
	include 'menu.php';
	?>
	
	</div>
	
<div class="content" align=center>
<table>
<?
	echo "<h3>User List</h3>";
	$stmt = $db->prepare("SELECT * FROM tblmembers left join webusers on tblmembers.MemberID=webusers.username order by tblmembers.Name");
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows != 0) 
		{
			echo '<table class="thintable"><tr><td><b>ID</b></td><td><b>Name</b></td><td><b>DB Team</b></td><td><b>Team</b></td><td><b>Access</b></td><td><b>Actions</b></td></tr>';
			while($row = $result->fetch_assoc()) {
				$MemberID = $row['MemberID'];
				$member = $WOLAPI->getMemberByNumber($row['MemberID']); 
				$team = $Availability->GetTeam($row['IDTeam']);
				
				
				  echo '<tr><td>' . $row['MemberID'] . '</td>' ;
				  echo '<td>' . $row['Name'] . '</td>';
				  if($team == $member[team])
				  {
					echo '<td>' . $team . '</td>';
				  }
				  else{
					  echo '<td class="red">' . $team . '</td>';
				  }
				
				  echo '<td>' . $member[team]. "</td>";
				  echo '<td>' . $row['access'] . '</td>';
				  echo '<td>' . '<a href="#" onclick="openSyncForm(' . $MemberID . ')"> Sync </a>' . '</td></tr>';
			}
			echo '</table>';
		}
		echo '<br><b>Inactive Members</b></br>';
		foreach($WOLAPI->DaptoMembers as $Member){
			
			if ($Availability->GetMemberName($Member['number']) == "")
			{
				echo '' . $Member['number'];
				echo ' ' . $Member['fullName'];
				echo ' ' . $Member['team'];
				echo '<br>';
			}
        }
	?>




</table>





	
</div>

<!-- The Modal -->
<div id="UserSyncModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Sync User Details</h2>
    </div>
    <div class="modal-body">
		<form name="SyncMember" action="adminUsers.php" method="post">
		  <p>Member ID:
			<input type="text" id="SyncMemberID" name="SyncMemberID" placeholder="Member ID" readonly required />
		  </p>
		  <p>Name:
			<input type="text" id="SyncName" name="SyncName" placeholder="Name" readonly required />
		  </p>
		  <p>Phone:
			<input type="text" id="SyncPhone" name="SyncPhone" placeholder="Phone" readonly required />
		  </p>
		  <p>Rank:
			<input type="text" id="SyncRank" name="SyncRank" placeholder="Rank" readonly />
		  </p>
		  <p>Team:
			<select name="SyncTeam" id="SyncTeam" disabled>
				<option value="1">Alpha</option>';
				<option value="2">Bravo</option>
				<option value="3">Seniors</option>
				<option selected value="4">Charlie</option>
			</select>
		  </p>
		  <button type="submit" name="UpdateMember" id="UpdateMember" class="btn">Comfirm Sync</button>
		  <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
		  
		  </form>
    </div>
  </div>

</div>


<script>

</script>

<?

	if ($_SESSION['authlevel']>1)
	{
		echo '<button class="open-button" onclick="openForm()">Add Member</button>';
			
			echo '<div class="form-popup" id="addMember">';
				echo '<form class="form-container" name="AddMember" action="adminUsers.php" method="post">';

						echo '<label for="MemberID"><b>Member ID</b></label>';
						echo '<input type="text" id="MemberID" name="MemberID" placeholder="Enter Member ID" required/>';
						echo '<button type="button" name="GetMemberDetails" id="GetMemberDetails" class="btn" onclick="GetUserDetails()">Get Member Details</button>';
						echo '<label for="name"><b>Name</b></label>';
						echo '<input type="text" id="Name" name="Name" placeholder="Enter Name" required/>';
						echo '<label for="Phone"><b>Phone</b></label>';
						echo '<input type="text" id="Phone" name="Phone" placeholder="Enter Phone number" required/>';
						echo '<label for="Team"><b>Team</b></label>';
						echo '<select name="Team" id="Team">';
						echo '<option selected value="1">Alpha</option>';
						echo '<option value="2">Bravo</option>';
						echo '<option value="3">Seniors</option>';
						echo '<option value="4">Charlie</option>';
						echo '</select>';
						echo '<br>';
						echo '<button type="submit" name="AddMember" id="AddMember" class="btn">Add Member</button>';
						echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
						

				echo '</form>';
			echo '</div>';
	}
	
	
			
			
	?>
<div id="results"></div>




<script>
		function GetUserDetails(){
				
					MemberID = document.getElementById("MemberID");
					Name  = document.getElementById("Name");
					Name.value = MemberID.value;
					$.ajax({
					  type: "POST",
					  url: "update_Users.php",
					  data: { MemberID: MemberID.value}
					}).done(function(response){
						var res = response.split(":");
						$("#Name").val(res[1].trim());
						$("#Phone").val(res[2].trim());
						$("#Team").val(4)
						 if(res[3].trim() == "Alpha") {
							 $("#Team").val(1);
						 }
						 if(res[3].trim() == "Bravo") {
							 $("#Team").val(2);
						 }
						 if(res[3].trim() == "Seniors") {
							 $("#Team").val(3);
						 }
						 if(res[3].trim() == "Charlie") {
							 $("#Team").val(4);
						 }
						$("#results").text(response);
						}
					
					
					
					
						);
				  
			
				
					
				
		};



	function openForm() {
	  document.getElementById("addMember").style.display = "block";
	};

	function closeForm() {
	  document.getElementById("addMember").style.display = "none";
	  document.getElementById("UserSyncModal").style.display = "none";
	};
	
	
	// Get the modal
	var modal = document.getElementById("UserSyncModal");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];
	
	function openSyncForm(MemberID) {
	  
	  txtMemberID  = document.getElementById("SyncMemberID");
					txtMemberID.value = MemberID;
					
					$("#SyncName").val("");
					$("#SyncPhone").val("");
					$("#SyncRank").val("");
					
					$.ajax({
					  type: "POST",
					  url: "update_Users.php",
					  data: { MemberID: MemberID}
					}).done(function(response){
						var res = response.split(":");
						$("#SyncName").val(res[1].trim());
						$("#SyncPhone").val(res[2].trim());
						$("#SyncRank").val(res[4].trim());
						$("#SyncTeam").val(4)
						 if(res[3].trim() == "Alpha") {
							 $("#SyncTeam").val(1);
						 }
						 if(res[3].trim() == "Bravo") {
							 $("#SyncTeam").val(2);
						 }
						 if(res[3].trim() == "Seniors") {
							 $("#SyncTeam").val(3);
						 }
						 if(res[3].trim() == "Charlie") {
							 $("#SyncTeam").val(4);
						 }
						$("#results").text(response);
						}
					
						);
		document.getElementById("UserSyncModal").style.display = "block";
	  
	  
	};

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
	  modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	  if (event.target == modal) {
		modal.style.display = "none";
	  }
	}
</script>

<script>

  var x = "Total Width: " + screen.width + "px";
  document.getElementById("results").innerHTML = x;

</script>




		