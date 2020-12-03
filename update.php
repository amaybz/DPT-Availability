	
<?


	session_start(); // Use session variable on this page.

	include 'tools/dbconnect.php';
	require_once( 'classes/Availability.php');


	if($_POST["MemberID"]){
		$MemberID = $_POST["MemberID"];
		$day = $_POST["day"];
		$value = $_POST["value"];
		$week = $_POST["week"];
		$TimeSlot = $_POST["slot"];
		
		//echo "ID:" . $MemberID;
		//echo " day:" . $day;
		//echo " week:" . $week; 
		//echo " TimeSlot:" . $TimeSlot; 
		//echo "Saved"; 
		
		if ($MemberID != "")
		{
			
			if($TimeSlot == "TimeSlot1") 
			{
				if($value != "")
					{
						//echo "db";
						// prepare and bind
						$stmt = $db->prepare("UPDATE tblAvailability SET MemberID=?, WeekID=?, DayID=?, TimeSlot1=? where MemberID=? and WeekID=? and DayID=?");
						$stmt->bind_param("iiiiiii", $MemberID, $week, $day, $value, $MemberID, $week, $day);
						$stmt->execute();
						if($stmt->affected_rows == 0)
						{
							$stmts = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? and DayID = ?");
							$stmts->bind_param("iii", $MemberID, $week, $day);
							$stmts->execute();
							$results = $stmts->get_result();
							//echo "Availability<br>";
							if($results->num_rows == 0) 
							{
									//Insert Row
									//echo "add required";
									// prepare and bind
									$stmt = $db->prepare("INSERT INTO tblAvailability (MemberID, WeekID, `DayID`, `TimeSlot1`)  VALUES (?, ?, ?, ?)");
									$stmt->bind_param("iiii", $MemberID, $week, $day, $value);
									$stmt->execute();		
							}
							
							
							
						}
						
					}
			}
			
			if($TimeSlot == "TimeSlot2") 
			{
				if($value != "")
					{
						//echo "db";
						// prepare and bind
						$stmt = $db->prepare("UPDATE tblAvailability SET MemberID=?, WeekID=?, DayID=?, TimeSlot2=? where MemberID=? and WeekID=? and DayID=?");
						$stmt->bind_param("iiiiiii", $MemberID, $week, $day, $value, $MemberID, $week, $day);
						$stmt->execute();
						if($stmt->affected_rows == 0)
						{
							
							$stmts = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? and DayID = ?");
							$stmts->bind_param("iii", $MemberID, $week, $day);
							$stmts->execute();
							$results = $stmts->get_result();
							//echo "Availability<br>";
							if($results->num_rows == 0) 
							{
								//Insert Row
								//echo "add required";
								// prepare and bind
								$stmt = $db->prepare("INSERT INTO tblAvailability (MemberID, WeekID, `DayID`, `TimeSlot2`)  VALUES (?, ?, ?, ?)");
								$stmt->bind_param("iiii", $MemberID, $week, $day, $value);
								$stmt->execute();
							}
						}
						
					}
			}
			
			if($TimeSlot == "TimeSlot3") 
			{

				if($value != "")
					{
						//echo "db";
						// prepare and bind
						$stmt = $db->prepare("UPDATE tblAvailability SET MemberID=?, WeekID=?, DayID=?, TimeSlot3=? where MemberID=? and WeekID=? and DayID=?");
						$stmt->bind_param("iiiiiii", $MemberID, $week, $day, $value, $MemberID, $week, $day);
						$stmt->execute();
						if($stmt->affected_rows == 0)
						{
							
							$stmts = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? and DayID = ?");
							$stmts->bind_param("iii", $MemberID, $week, $day);
							$stmts->execute();
							$results = $stmts->get_result();
							//echo "Availability<br>";
							if($results->num_rows == 0) 
							{
								//Insert Row
								//echo "add required";
								// prepare and bind
								$stmt = $db->prepare("INSERT INTO tblAvailability (MemberID, WeekID, `DayID`, `TimeSlot3`)  VALUES (?, ?, ?, ?)");
								$stmt->bind_param("iiii", $MemberID, $week, $day, $value);
								$stmt->execute();
							}
						}
						
					}
			}
			if($TimeSlot == "TimeSlot4") 
			{

				if($value != "")
					{
						//echo "db";
						// prepare and bind
						$stmt = $db->prepare("UPDATE tblAvailability SET MemberID=?, WeekID=?, DayID=?, TimeSlot4=? where MemberID=? and WeekID=? and DayID=?");
						$stmt->bind_param("iiiiiii", $MemberID, $week, $day, $value, $MemberID, $week, $day);
						$stmt->execute();
						if($stmt->affected_rows == 0)
						{
							$stmts = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? and DayID = ?");
							$stmts->bind_param("iii", $MemberID, $week, $day);
							$stmts->execute();
							$results = $stmts->get_result();
							//echo "Availability<br>";
							if($results->num_rows == 0) 
							{
								//Insert Row
								//echo "add required";
								// prepare and bind
								$stmt = $db->prepare("INSERT INTO tblAvailability (MemberID, WeekID, `DayID`, `TimeSlot4`)  VALUES (?, ?, ?, ?)");
								$stmt->bind_param("iiii", $MemberID, $week, $day, $value);
								$stmt->execute();
							}
						}
						
					}
			}
			
			if($TimeSlot == "TimeSlot5") 
			{

				if($value != "")
					{
						//echo "db";
						// prepare and bind
						$stmt = $db->prepare("UPDATE tblAvailability SET MemberID=?, WeekID=?, DayID=?, TimeSlot5=? where MemberID=? and WeekID=? and DayID=?");
						$stmt->bind_param("iiiiiii", $MemberID, $week, $day, $value, $MemberID, $week, $day);
						$stmt->execute();
						if($stmt->affected_rows == 0)
						{
							$stmts = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? and DayID = ?");
							$stmts->bind_param("iii", $MemberID, $week, $day);
							$stmts->execute();
							$results = $stmts->get_result();
							//echo "Availability<br>";
							if($results->num_rows == 0) 
							{
								//Insert Row
								//echo "add required";
								// prepare and bind
								$stmt = $db->prepare("INSERT INTO tblAvailability (MemberID, WeekID, `DayID`, `TimeSlot5`)  VALUES (?, ?, ?, ?)");
								$stmt->bind_param("iiii", $MemberID, $week, $day, $value);
								$stmt->execute();
							}
						}
						
					}
			}
			
			if($TimeSlot == "TimeSlot6") 
			{

				if($value != "")
					{
						//echo "db";
						// prepare and bind
						$stmt = $db->prepare("UPDATE tblAvailability SET MemberID=?, WeekID=?, DayID=?, TimeSlot6=? where MemberID=? and WeekID=? and DayID=?");
						$stmt->bind_param("iiiiiii", $MemberID, $week, $day, $value, $MemberID, $week, $day);
						$stmt->execute();
						if($stmt->affected_rows == 0)
						{
							
							$stmts = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? and DayID = ?");
							$stmts->bind_param("iii", $MemberID, $week, $day);
							$stmts->execute();
							$results = $stmts->get_result();
							//echo "Availability<br>";
							if($results->num_rows == 0) 
							{
								//Insert Row
								//echo "add required";
								// prepare and bind
								$stmt = $db->prepare("INSERT INTO tblAvailability (MemberID, WeekID, `DayID`, `TimeSlot6`)  VALUES (?, ?, ?, ?)");
								$stmt->bind_param("iiii", $MemberID, $week, $day, $value);
								$stmt->execute();
							}
						}
						
					}
			}
			
			if($TimeSlot == "TimeSlot7") 
			{

				if($value != "")
					{
						//echo "db";
						// prepare and bind
						$stmt = $db->prepare("UPDATE tblAvailability SET MemberID=?, WeekID=?, DayID=?, TimeSlot7=? where MemberID=? and WeekID=? and DayID=?");
						$stmt->bind_param("iiiiiii", $MemberID, $week, $day, $value, $MemberID, $week, $day);
						$stmt->execute();
						if($stmt->affected_rows == 0)
						{
							$stmts = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? and DayID = ?");
							$stmts->bind_param("iii", $MemberID, $week, $day);
							$stmts->execute();
							$results = $stmts->get_result();
							//echo "Availability<br>";
							if($results->num_rows == 0) 
							{
								//Insert Row
								//echo "add required";
								// prepare and bind
								$stmt = $db->prepare("INSERT INTO tblAvailability (MemberID, WeekID, `DayID`, `TimeSlot7`)  VALUES (?, ?, ?, ?)");
								$stmt->bind_param("iiii", $MemberID, $week, $day, $value);
								$stmt->execute();
							}
						}
						
					}
			}
			
			if($TimeSlot == "TimeSlot8") 
			{

				if($value != "")
					{
						//echo "db";
						// prepare and bind
						$stmt = $db->prepare("UPDATE tblAvailability SET MemberID=?, WeekID=?, DayID=?, TimeSlot8=? where MemberID=? and WeekID=? and DayID=?");
						$stmt->bind_param("iiiiiii", $MemberID, $week, $day, $value, $MemberID, $week, $day);
						$stmt->execute();
						if($stmt->affected_rows == 0)
						{
							$stmts = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? and DayID = ?");
							$stmts->bind_param("iii", $MemberID, $week, $day);
							$stmts->execute();
							$results = $stmts->get_result();
							//echo "Availability<br>";
							if($results->num_rows == 0) 
							{
								//Insert Row
								//echo "add required";
								// prepare and bind
								$stmt = $db->prepare("INSERT INTO tblAvailability (MemberID, WeekID, `DayID`, `TimeSlot8`)  VALUES (?, ?, ?, ?)");
								$stmt->bind_param("iiii", $MemberID, $week, $day, $value);
								$stmt->execute();
							}
						}
						
					}
			}
			
			
			

			

				
		}
		else
		{
			$message = "Update Failed, All Fields are Required.";
		}
		
	}
	
?>