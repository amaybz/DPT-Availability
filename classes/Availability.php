<?
class Availability
{
	
    /**
     * @var string ActiveRecordID
     */
    private $ActiveRecordID  = 0;
	
	/**
     * @var string ActiveRecord	
     */
    public $ActiveRecord = 0;
	
	
	
	
	
    
    public function __construct()
    {
		// Change the line below to your timezone!
	date_default_timezone_set('Australia/Melbourne');
	}
	
	
	
	
	public function GetDayShort($dayID)
    {
		if($dayID == 0)
		{
			$day = "Tues";
		}
		else if($dayID == 1)
		{
			$day = "Wed";
		}
		else if($dayID == 2)
		{
			$day = "Thurs";
		}
		else if($dayID == 3)
		{
			$day = "Fri";
		}
		else if($dayID == 4)
		{
			$day = "Sat";
		}
		else if($dayID == 5)
		{
			$day = "Sun";
		}
		else if($dayID == 6)
		{
			$day = "Mon";
		}
		
		return $day;
	}
	
	public function GetMemberName($MemberID)
    {
		include './tools/dbconnect.php';
		$stmt = $db->prepare("SELECT * FROM tblmembers WHERE `MemberID`=?");
		$stmt->bind_param("i", $MemberID);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows != 0) 
		{
			while($row = $result->fetch_assoc()) {
				 $MemberName = $row['Name'];
			}
		}
		$stmt->close();
		return $MemberName;
	}
	
	public function GetTeam($IDTeam)
    {
		include './tools/dbconnect.php';
		$stmt = $db->prepare("SELECT * FROM tblteams WHERE `IDTeam`=?");
		$stmt->bind_param("i", $IDTeam);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows != 0) 
		{
			while($row = $result->fetch_assoc()) {
				 $TeamName = $row['Team Name'];
			}
		}
		$stmt->close();
		return $TeamName;
	}
	
	public function GetTeamID($Team)
    {
		$IDTeam = 0;
		include './tools/dbconnect.php';
		$stmt = $db->prepare("SELECT * FROM tblteams WHERE `Team Name`=?");
		$stmt->bind_param("s", $Team);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows != 0) 
		{
			while($row = $result->fetch_assoc()) {
				 $IDTeam = $row['IDTeam'];
			}
		}
		$stmt->close();
		return $IDTeam;
	}
	
	public function GetRankID($Rank)
    {
		$IDRank = 0;
		include './tools/dbconnect.php';
		$stmt = $db->prepare("SELECT * FROM tblRanks WHERE `RankName`=?");
		$stmt->bind_param("s", $Rank);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows != 0) 
		{
			while($row = $result->fetch_assoc()) {
				 $RankID = $row['RankID'];
			}
		}
		$stmt->close();
		return $RankID;
	}
	
	
	public function AddMember($MemberID,$Name,$Phone,$Team)
    {
		include './tools/dbconnect.php';
		$stmt = $db->prepare("INSERT INTO `tblmembers` (`MemberID`, `Name`, `Contact`, `IDTeam`) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("isii", $MemberID,$Name,$Phone,$Team);
		$stmt->execute();
		$stmt->close();
		return true;
	}
	
	public function CountAvailabilityByDay($Week,$Day,$TimeSlot,$Team)
    {
		date_default_timezone_set('Australia/Melbourne');
		include './tools/dbconnect.php';
		
		$AvailabilityCount = 0;
		
		if($Team == 0)
		{
			$sql="SELECT * FROM tblAvailability WHERE WeekID=? and DayID=? AND TimeSlot" . $TimeSlot . "=1;";
			$stmt = $db->prepare($sql);
			$stmt->bind_param("ii", $Week,$Day);
			
			if (!$stmt->execute())
			{echo "execute FAILED" . $stmt->error;}
			
			$result = $stmt->get_result();
			if($result->num_rows != 0) 
			{
				$AvailabilityCount = $result->num_rows;
			}
			$stmt->close();
			
		}
		else
		{
			
			
			$sql="SELECT * FROM vwMemberAvailability WHERE WeekID=" . $Week . "  and DayID=" . $Day . " AND TimeSlot" . $TimeSlot . "=1 and IDTeam=" . $Team ;	
			$result = $db->query($sql);
			$RowCount = $result->num_rows;
			if ($RowCount > 0)
			{
				$AvailabilityCount = $RowCount;
			}		
			
		}

		return $AvailabilityCount;	
		
		
		
	}
	
	
	public function GetCurrentTimeSlot()
    {
		include './tools/dbconnect.php';
		date_default_timezone_set('Australia/Melbourne');
		$CurrentTime = date("H");

		if($CurrentTime > 0 and $CurrentTime < 3) 
		{$TimeSlot = 1;}
		if($CurrentTime > 2 and $CurrentTime < 6) 
		{$TimeSlot = 2;}
		if($CurrentTime > 5 and $CurrentTime < 9) 
		{$TimeSlot = 3;}
		if($CurrentTime > 8 and $CurrentTime < 12) 
		{$TimeSlot = 4;}
		if($CurrentTime > 11 and $CurrentTime < 15) 
		{$TimeSlot = 5;}
		if($CurrentTime > 14 and $CurrentTime < 18)  
		{$TimeSlot = 6;}
		if($CurrentTime > 17 and $CurrentTime < 21) 
		{$TimeSlot = 7;}
		if($CurrentTime > 20 and $CurrentTime < 24) 
		{$TimeSlot = 8;}
	
		return $TimeSlot;
	}
	
	
	public function CheckIfAvailable($MemberID)
    {
		include './tools/dbconnect.php';
		date_default_timezone_set('Australia/Melbourne');
		
		
		
		$day = date('w');
		
		

		if($day > 1 and $day < 7 )
			{
				$day = $day - 2;
			}
		else if ($day < 2)
		{
			$day = $day + 5;
		}
		
		$week = date('W', strtotime('-'.$day.' days'));
		
		//echo "Day:" . $day . " week:" . $week . " " ;
		
	
		
	
		//echo $CurrentTime;
		//echo "Timeslot:" . $TimeSlot;
		$TimeSlot = $this->GetCurrentTimeSlot();
		
		//echo " SELECT * FROM vwMemberAvailability WHERE MemberID=" . $MemberID. " and DayID=" . $day. " and WeekID=" . $week. " and TimeSlot" . $TimeSlot . "=1 " ;
		$sql = "SELECT * FROM tblAvailability WHERE MemberID=? and DayID=? and WeekID=? and TimeSlot" . $TimeSlot . "=1";
		//echo "<br>" . $sql;
		//echo $MemberID . " " . $day . " " .  $week;
		$stmt = $db->prepare($sql);
		$stmt->bind_param("iii", $MemberID, $day, $week);
		$stmt->execute();
		$result = $stmt->get_result();
		
		//echo "rows:" . $result->num_rows;
		if($result->num_rows > 0) 
		{
			$Availability = "1";
		}
		else
		{
			$Availability = "0";
		}
		$stmt->close();
		return $Availability;
	}
	
	
	
	
	
}


?>