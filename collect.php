<? 
	
	session_start(); // Use session variable on this page.

	require_once( 'classes/Users.php');

	$Users = new Users();
	
	if($Users->CheckIfSignedIn() == false) { // if session variable "username" does not exist.
		header('Location: login.php');
		exit;
	}
	//uncomment the below 2 lines for debugging.
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	
	include 'tools/dbconnect.php';
	require_once( 'classes/Availability.php');
	
	$Availability = new Availability();
	
	
	
	if($_GET["MemberID"]){
		$MemberID = $_GET["MemberID"];
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>DPT SES</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico">
	</head>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<body onload="onload()">
	<div class="head">
		<img src="logo.2b1db366.svg" alt="SES Logo" width="20" height="20">
		Dapto Availability - Collection
	</div>
	
	<div class="menuInline" align="center">
	<? 
	include 'menu.php';
	?>
		 
	</div>
	<div class="content" align=center>
		<?
			
			
			
			$day = date('w');
			//echo $day . "<br>";
			//$day = 1;
			if($day > 1 and $day < 7 )
				{
					$day = $day - 2;
				}
			else if ($day < 2)
			{
				$day = $day + 5;
			}
				
			
			if($_GET["Week"] == 1){
				$lastweek = 1;
			}
			if($_GET["Week"] == 2){
				$nextweek = 1;
			}
			
			
			//echo "Day: " . $Availability->GetDayShort($day) . "<br>";
			$week = date('W', strtotime('-'.$day.' days'));
			if($day == 6){
				$week = $week + 1;
				$day = $day - 7;
			}
			if($lastweek == 1)
			{
				$week = $week - 1;
				$day = $day + 7;
			}
			if($nextweek == 1)
			{
				$week = $week + 1;
				$day = $day - 7;
			}
			echo "Week# " . $week . "<br>";
			$week_start = date('d-m-Y', strtotime('-'.$day.' days'));
			echo "Start Date: " . $week_start . "<br>";
			$week_end = date('d-m-Y', strtotime('+'.(6-$day).' days'));
			echo "End date: " . $week_end . "<br>";
			$day1 = date('d-m-Y', strtotime($week_start . ' +1 day'));
			$day2 = date('d-m-Y', strtotime($week_start . ' +2 days'));
			$day3 = date('d-m-Y', strtotime($week_start . ' +3 days'));
			$day4 = date('d-m-Y', strtotime($week_start . ' +4 days'));
			$day5 = date('d-m-Y', strtotime($week_start . ' +5 days'));
			$day6 = date('d-m-Y', strtotime($week_start . ' +6 days'));
			
			$shortdate[0] = date('d/m', strtotime($week_start));
			$shortdate[1] = date('d/m', strtotime($week_start . ' +1 day'));
			$shortdate[2] = date('d/m', strtotime($week_start . ' +2 day'));
			$shortdate[3] = date('d/m', strtotime($week_start . ' +3 day'));
			$shortdate[4] = date('d/m', strtotime($week_start . ' +4 day'));
			$shortdate[5] = date('d/m', strtotime($week_start . ' +5 day'));
			$shortdate[6] = date('d/m', strtotime($week_start . ' +6 day'));
			
			
			
		?>
	
		<div class="MemberSelect">
			<form id="frmMemberSelect"> 
			Select Member:
			<select name='MemberID' id='MemberID'  onchange='document.getElementById("frmMemberSelect").submit();'>
				<?
					echo '<option value="0">Select Member</option>';
					$sql = "SELECT * FROM tblmembers order by Name";
					$resultStaff = $db->query($sql);
					$RowCountStaff = $resultStaff->num_rows;
					
					if ($RowCountStaff > 0)
					{
						while($rowStaff = $resultStaff->fetch_assoc()){
							//select		
							if ($rowStaff["MemberID"] == $MemberID){
								echo '<option selected value="' . $rowStaff["MemberID"] . '">' . $rowStaff["Name"] . '</option>';
							}
							else{
								echo '<option value="' . $rowStaff["MemberID"] . '">' . $rowStaff["Name"] . '</option>';
							}

						}
					}
					else
					{
						echo '<option value="0">No Staff in DB</option>';
					}
					echo '</select>';
					
					
				?>
			
			</form>
		</div>
		<?
		echo "<a href='collect.php?Week=1&MemberID=" . $MemberID . "'> Last Week </a>| <a href='collect.php?Week=0&MemberID=" . $MemberID . "'> Current Week </a> | <a href='collect.php?Week=2&MemberID=" . $MemberID . "'> Next Week </a>";
		
		if($MemberID == 0)
		{
			$hideme = " hide";
		}
		?>
	
		<div class="Availability<? echo $hideme;?>">
			<Table class="thintable"> 
			<tr><td>Day\Time</td><td>0000<br>0300</td><td>0300<br>0600</td><td>0600<br>0900</td><td>0900<br>1200</td><td>1200<br>1500</td><td>1500<br>1800</td><td>1800<br>2100</td><td>2100<br>0000</td></tr>
			
			<?
		
			$stmt = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? ORDER BY `DayID` ASC");
			$stmt->bind_param("ii", $MemberID, $week);
			$stmt->execute();
			$result = $stmt->get_result();
			//echo "Availability<br>";
			if($result->num_rows != 0) 
			{
				
				$DayCount = 0;
				while($row = $result->fetch_assoc()) {
					  $DayID = $row['DayID'];
					  $TimeSlot1 = $row['TimeSlot1'];
					  $TimeSlot2 = $row['TimeSlot2'];
					  $TimeSlot3 = $row['TimeSlot3'];
					  $TimeSlot4 = $row['TimeSlot4'];
					  $TimeSlot5 = $row['TimeSlot5'];
					  $TimeSlot6 = $row['TimeSlot6'];
					  $TimeSlot7 = $row['TimeSlot7'];
					  $TimeSlot8 = $row['TimeSlot8'];
					  
					  
					 //fill days before data starts
					for ($x = $DayCount; $x < $DayID; $x++) {
						echo "<tr><td>" . $Availability->GetDayShort($DayCount) . " " . $shortdate[$DayCount] . "</td>";
						echo '<td><input type="checkbox"  name="TimeSlot1" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot2" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot3" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot4" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot5" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot6" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot7" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot8" id="' . $DayCount . '" /></td>';
						
						
						echo "</tr>";
						$DayCount++;
					}
					 //add day with data
					if($DayCount == $DayID)
					{
						echo  "<tr><td>" . $Availability->GetDayShort($DayID) . " " . $shortdate[$DayCount] . "</td>";
						if($TimeSlot1 == 1)
						{echo '<td class="green"><input  type="checkbox" checked name="TimeSlot1" id="' . $DayCount . '" /></td>';}
						else
						{echo '<td class="red"><input type="checkbox" name="TimeSlot1" id="' . $DayCount . '" /></td>';}
					
						if($TimeSlot2 == 1)
						{echo '<td class="green"><input type="checkbox" checked name="TimeSlot2" id="' . $DayCount . '" /></td>';}
						else
						{echo '<td class="red"><input type="checkbox" name="TimeSlot2" id="' . $DayCount . '" /></td>';}
					
						if($TimeSlot3 == 1)
						{echo '<td class="green"><input type="checkbox" checked name="TimeSlot3" id="' . $DayCount . '" /></td>';}
						else
						{echo '<td class="red"><input type="checkbox" name="TimeSlot3" id="' . $DayCount . '" /></td>';}
					
						if($TimeSlot4 == 1)
						{echo '<td class="green"><input type="checkbox" checked name="TimeSlot4" id="' . $DayCount . '" /></td>';}
						else
						{echo '<td class="red"><input type="checkbox" name="TimeSlot4" id="' . $DayCount . '" /></td>';}
						
						if($TimeSlot5 == 1)
						{echo '<td class="green"><input type="checkbox" checked name="TimeSlot5" id="' . $DayCount . '" /></td>';}
						else
						{echo '<td class="red"><input type="checkbox" name="TimeSlot5" id="' . $DayCount . '" /></td>';}
						
						if($TimeSlot6 == 1)
						{echo '<td class="green"><input type="checkbox" checked name="TimeSlot6" id="' . $DayCount . '" /></td>';}
						else
						{echo '<td class="red"><input type="checkbox" name="TimeSlot6" id="' . $DayCount . '" /></td>';}
						
						if($TimeSlot7 == 1)
						{echo '<td class="green"><input type="checkbox" checked name="TimeSlot7" id="' . $DayCount . '" /></td>';}
						else
						{echo '<td class="red"><input type="checkbox" name="TimeSlot7" id="' . $DayCount . '" /></td>';}
						
						if($TimeSlot8 == 1)
						{echo '<td class="green"><input type="checkbox" checked name="TimeSlot8" id="' . $DayCount . '" /></td>';}
						else
						{echo '<td class="red"><input type="checkbox" name="TimeSlot8" id="' . $DayCount . '" /></td>';}
						
						echo "</tr>";
							
					}
					//increase count
					$DayCount++;
					
				}
				//Update remaining Days with no data
				for ($x = $DayCount; $x < 7; $x++) {
						echo "<tr><td>" . $Availability->GetDayShort($DayCount) . " " . $shortdate[$DayCount] . "</td>";
						echo '<td><input type="checkbox"  name="TimeSlot1" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot2" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot3" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot4" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot5" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot6" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot7" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot8" id="' . $DayCount . '" /></td>';
						echo "</tr>";
						$DayCount++;
				}
				
			}
			else
			{
			//no data at all, fill with empty table
			$DayCount = 0;
			$DayID = 7;
			for ($x = $DayCount; $x < $DayID; $x++) {
						echo "<tr><td>" . $Availability->GetDayShort($DayCount) . " " . $shortdate[$DayCount] . "</td>";
						echo '<td><input type="checkbox"  name="TimeSlot1" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot2" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot3" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot4" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot5" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot6" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot7" id="' . $DayCount . '" /></td>';
						echo '<td><input type="checkbox"  name="TimeSlot8" id="' . $DayCount . '" /></td>';
						echo "</tr>";
						$DayCount++;
					}
				
				
			}
			$stmt->close();
		
		?>
			
			
					
			
			</table>
		</div>
		<div id="results"></div>
	</div>
<script>

		function UpdateAvailability(input){
				
				if(input.checked){
					$.ajax({
					  type: "POST",
					  url: "update.php",
					  data: { day: input.id, slot: input.name, value: 1, week: <? echo $week; ?>, MemberID: <? echo $MemberID; ?> }
					}).done(function(response){$("#results").text(response);});
				}
				else{
					$.ajax({
					  type: "POST",
					  url: "update.php",
					  data: { day: input.id, slot: input.name, value: 0, week: <? echo $week; ?>, MemberID: <? echo $MemberID; ?> }
					}).done(function(response){$("#results").text(response);});
				  
				}
				
					
				
		};



//TD Click
		
		function onload() {
                var tds = document.getElementsByTagName("td");
                for(var i = 0; i < tds.length; i++) {
                    tds[i].onclick = 
                                    function(td) { 
                                        return function() { 
                                            tdOnclick(td); 
                                        }; 
                                    }(tds[i]); 
                }
                var inputs = document.getElementsByTagName("input");
                for(var i = 0; i < inputs.length; i++) {
                    inputs[i].onclick = 
                                    function(input){ 
                                        return function() { 
                                            inputOnclick(input); 
                                        };
                                    }(inputs[i]); 
                }
            }
            function tdOnclick(td) {
                for(var i = 0; i < td.childNodes.length; i++) {
                    if(td.childNodes[i].nodeType == 1) {
                        if(td.childNodes[i].nodeName == "INPUT") {
                            if(td.childNodes[i].checked) {
                                td.childNodes[i].checked = false;
                                //td.style.backgroundColor = "red";
								td.className = "Red";
								UpdateAvailability(td.childNodes[i]);
                            } else {
                                td.childNodes[i].checked = true;
                                //td.style.backgroundColor = "green";
								td.className = "green";
								UpdateAvailability(td.childNodes[i]);
                            }
                        } else {
                            tdOnclick(td.childNodes[i]);
                        }
                    }
                }
            }
            function inputOnclick(input) {
                input.checked = !input.checked;
				
                return false;
            }
		
	</script>


