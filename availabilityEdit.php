<? 
	
	session_start(); // Use session variable on this page.

	require_once( 'classes/Users.php');

	$Users = new Users();
	if($Users->CheckIfSignedIn() == false or $_SESSION['authlevel'] < 1) { // if session variable "username" does not exist.
		$Users->ShowAccessDenied();
		exit;
	}
	
	include 'tools/dbconnect.php';
	require_once( 'classes/Availability.php');
	
	$Availability = new Availability();
	
	
	$MemberID = 0;
	if($_GET["IDTeam"]){
		$IDTeam = $_GET["IDTeam"];
		$_SESSION['IDTeam'] = $_GET["IDTeam"];
	}
	else
	{
		$_SESSION['IDTeam'] = $_GET["IDTeam"];
		$IDTeam = $_SESSION["IDTeam"];
	}
		
	if($_GET["Week"] == 0){
			$_SESSION['Week'] = $_GET["Week"];
	}
	if($_GET["Week"] == 1){
			$lastweek = 1;
			$_SESSION['Week'] = $_GET["Week"];
	}
	if($_GET["Week"] == 2){
			$nextweek = 1;
			$_SESSION['Week'] = $_GET["Week"];
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
	<body onload="onload()">
	<div class="head">
		<img src="logo.2b1db366.svg" alt="SES Logo" width="20" height="20">
		Dapto Availability - Unit
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
			echo "<b>Start Date:</b> " . $week_start . "<br>";
			$week_end = date('d-m-Y', strtotime('+'.(6-$day).' days'));
			echo "<b>End date: </b>" . $week_end . "<br>";
			
			$day1 = date('d-m-Y', strtotime($week_start . ' +1 day'));
			$day2 = date('d-m-Y', strtotime($week_start . ' +2 days'));
			$day3 = date('d-m-Y', strtotime($week_start . ' +3 days'));
			$day4 = date('d-m-Y', strtotime($week_start . ' +4 days'));
			$day5 = date('d-m-Y', strtotime($week_start . ' +5 days'));
			$day6 = date('d-m-Y', strtotime($week_start . ' +6 days'));
			
			echo '<div class="filters" align="center">';
			//echo '<div class="WeekSelect">';
			//echo "<a href='availability.php?Week=1'> Last Week </a>| <a href='availability.php?Week=0'> Current Week </a> | <a href='availability.php?Week=2'> Next Week </a>";
			?>
			
			<div class="WeekSelect">
			
				<form id="frmfilersSelect"> 
				Select Week:
				<select name='Week' id='Week'  onchange='document.getElementById("frmfilersSelect").submit();'>
				
			
			<?
			if ($_SESSION['Week'] == 0)
			{
				echo '<option selected value="0">Current Week</option>';
			}
			else{
				echo '<option value="0">Current Week</option>';
			}
			if ($_SESSION['Week'] == 1)
			{
				echo '<option selected value="1">Last Week</option>';
			}
			else{
				echo '<option value="1">Last Week</option>';
			}
			if ($_SESSION['Week'] == 2)
			{
				echo '<option selected value="2">Next Week</option>';
			}
			else{
				echo '<option value="2">Next Week</option>';
			}
			echo '</select>';
			echo '</div>';
			
			
		?>
		
			
		
			<div class="TeamSelect">
			
				<form id="frmTeamSelect"> 
				Select Team:
				<select name='IDTeam' id='IDTeam'  onchange='document.getElementById("frmfilersSelect").submit();'>
					<?
						echo '<option value="0">All Teams</option>';
						$sql = "SELECT * FROM tblteams";
						$resultTeams = $db->query($sql);
						$RowCountTeams = $resultTeams->num_rows;
						
						if ($RowCountTeams > 0)
						{
							while($rowTeams = $resultTeams->fetch_assoc()){
								//select		
								if ($rowTeams["IDTeam"] == $IDTeam){
									echo '<option selected value="' . $rowTeams["IDTeam"] . '">' . $rowTeams["Team Name"] . '</option>';
								}
								else{
									echo '<option value="' . $rowTeams["IDTeam"] . '">' . $rowTeams["Team Name"] . '</option>';
								}

							}
						}
						else
						{
							echo '<option value="0">No Teams in DB</option>';
						}
						echo '</select>';
						
						
					?>
				
				</form>
			</div>
		</div>
		
		<div class="Linebreak">
		<br>
		</div>
	

			<Table class="tableexport"> 
			<tr><th class=''></th><th  class="newday" colspan=8>Tuesday <? echo $week_start; ?></th><th  class="newday" colspan=8>Wednesday <? echo $day1; ?></th><th  class="newday" colspan=8>Thursday <? echo $day2; ?></th><th  class="newday" colspan=8>Friday <? echo $day3; ?></th><th class="newday" colspan=8>Saturday <? echo $day4; ?></th><th class="newday" colspan=8>Sunday <? echo $day5; ?></th><th  class="newday" colspan=8>Monday <? echo $day6; ?></th></tr>
			<tr><th class="stick">Member</th>
			<th class="newday stick">0000<br>0400</th><th class="stick">0400<br>0800</th><th class="stick">0800<br>1000</th><th class="stick">1000<br>1200</th><th class="stick">1200<br>1600</th><th class="stick">1600<br>1800</td><th class="stick">1800<br>2000</td><th class="stick">2000<br>0000</th>
			<th class="newday stick">0000<br>0400</th><th class="stick">0400<br>0800</th><th class="stick">0800<br>1000</th><th class="stick">1000<br>1200</th><th class="stick">1200<br>1600</th><th class="stick">1600<br>1800</td><th class="stick">1800<br>2000</td><th class="stick">2000<br>0000</th>
			<th class="newday stick">0000<br>0400</th><th class="stick">0400<br>0800</th><th class="stick">0800<br>1000</th><th class="stick">1000<br>1200</th><th class="stick">1200<br>1600</th><th class="stick">1600<br>1800</td><th class="stick">1800<br>2000</td><th class="stick">2000<br>0000</th>
			<th class="newday stick">0000<br>0400</th><th class="stick">0400<br>0800</th><th class="stick">0800<br>1000</th><th class="stick">1000<br>1200</th><th class="stick">1200<br>1600</th><th class="stick">1600<br>1800</td><th class="stick">1800<br>2000</td><th class="stick">2000<br>0000</th>
			<th class="newday stick">0000<br>0400</th><th class="stick">0400<br>0800</th><th class="stick">0800<br>1000</th><th class="stick">1000<br>1200</th><th class="stick">1200<br>1600</th><th class="stick">1600<br>1800</td><th class="stick">1800<br>2000</td><th class="stick">2000<br>0000</th>
			<th class="newday stick">0000<br>0400</th><th class="stick">0400<br>0800</th><th class="stick">0800<br>1000</th><th class="stick">1000<br>1200</th><th class="stick">1200<br>1600</th><th class="stick">1600<br>1800</td><th class="stick">1800<br>2000</td><th class="stick">2000<br>0000</th>
			<th class="newday stick">0000<br>0400</th><th class="stick">0400<br>0800</th><th class="stick">0800<br>1000</th><th class="stick">1000<br>1200</th><th class="stick">1200<br>1600</th><th class="stick">1600<br>1800</td><th class="stick">1800<br>2000</td><th class="stick">2000<br>0000</th>
			</tr>
			
			<?
			if($IDTeam > 0) 
			{
				$stmtMembers = $db->prepare("SELECT * FROM tblmembers where IDTeam=?");
				$stmtMembers->bind_param("i", $IDTeam);
			}
			else
			{
				$stmtMembers = $db->prepare("SELECT * FROM tblmembers");
			}
				
			
			
			$stmtMembers->execute();
			$resultMembers = $stmtMembers->get_result();
			if($resultMembers->num_rows != 0) 
			{
				$DayCount = 0;
				while($rowMember = $resultMembers->fetch_assoc()) 
				{
					
						if($MemberID > 0)
						 {
							 //Update remaining Days with no data
							for ($x = $DayCount; $x < 7; $x++) {
								//echo "<td>" . $Availability->GetDayShort($DayCount) . "</td>";
								echo '<td class="newday"><input type="checkbox"  name="TimeSlot1" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot2" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot3" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot4" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot5" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot6" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot7" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot8" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								//echo "</tr>";
								$DayCount++;
							}
						 }
						 
						 $MemberID = $rowMember['MemberID'];
						 
						 
						 
						 echo "</tr><tr>";
						 $DayCount = 0;
						 echo "<td class=''><b>" . $Availability->GetMemberName($MemberID) . "</b></td>";
					 
					 
					
					
					$stmt = $db->prepare("SELECT * FROM tblAvailability WHERE `MemberID` = ? AND WeekID = ? ORDER BY `DayID` ASC");
					$stmt->bind_param("ii", $MemberID, $week);
					$stmt->execute();
					$result = $stmt->get_result();
					//echo "Availability<br>";
					if($result->num_rows != 0) 
					{
						
						
						while($row = $result->fetch_assoc()) {
							 
							 
							 
							  $DayID = $row['DayID'];
							  $MemberID = $row['MemberID'];
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
								
								echo '<td class="newday"><input type="checkbox"  name="TimeSlot1" id="' . $DayCount . '" value="' . $MemberID . '" /></td>';
								echo '<td><input type="checkbox"  name="TimeSlot2" id="' . $DayCount . '" value="' . $MemberID . '" /></td>';
								echo '<td><input type="checkbox"  name="TimeSlot3" id="' . $DayCount . '" value="' . $MemberID . '" /></td>';
								echo '<td><input type="checkbox"  name="TimeSlot4" id="' . $DayCount . '" value="' . $MemberID . '" /></td>';
								echo '<td><input type="checkbox"  name="TimeSlot5" id="' . $DayCount . '" value="' . $MemberID . '" /></td>';
								echo '<td><input type="checkbox"  name="TimeSlot6" id="' . $DayCount . '" value="' . $MemberID . '" /></td>';
								echo '<td><input type="checkbox"  name="TimeSlot7" id="' . $DayCount . '" value="' . $MemberID . '" /></td>';
								echo '<td><input type="checkbox"  name="TimeSlot8" id="' . $DayCount . '" value="' . $MemberID . '" /></td>';
								
								
								//echo "</tr>";
								$DayCount++;
							}
							 //add day with data
							if($DayCount == $DayID)
							{
								
								if($TimeSlot1 == 1)
								{echo '<td class="green newday"><input  type="checkbox" checked name="TimeSlot1" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								else
								{echo '<td class="red newday"><input type="checkbox" name="TimeSlot1" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
							
								if($TimeSlot2 == 1)
								{echo '<td class="green"><input type="checkbox" checked name="TimeSlot2" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								else
								{echo '<td class="red"><input type="checkbox" name="TimeSlot2" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
							
								if($TimeSlot3 == 1)
								{echo '<td class="green"><input type="checkbox" checked name="TimeSlot3" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								else
								{echo '<td class="red"><input type="checkbox" name="TimeSlot3" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
							
								if($TimeSlot4 == 1)
								{echo '<td class="green"><input type="checkbox" checked name="TimeSlot4" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								else
								{echo '<td class="red"><input type="checkbox" name="TimeSlot4" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								
								if($TimeSlot5 == 1)
								{echo '<td class="green"><input type="checkbox" checked name="TimeSlot5" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								else
								{echo '<td class="red"><input type="checkbox" name="TimeSlot5" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								
								if($TimeSlot6 == 1)
								{echo '<td class="green"><input type="checkbox" checked name="TimeSlot6" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								else
								{echo '<td class="red"><input type="checkbox" name="TimeSlot6" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								
								if($TimeSlot7 == 1)
								{echo '<td class="green"><input type="checkbox" checked name="TimeSlot7" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								else
								{echo '<td class="red"><input type="checkbox" name="TimeSlot7" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								
								if($TimeSlot8 == 1)
								{echo '<td class="green"><input type="checkbox" checked name="TimeSlot8" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								else
								{echo '<td class="red"><input type="checkbox" name="TimeSlot8" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';}
								
								//echo "</tr>";
									
							}
							//increase count
							$DayCount++;
							
						}
						//Update remaining Days with no data
						for ($x = $DayCount; $x < 7; $x++) {
								//echo "<td>" . $Availability->GetDayShort($DayCount) . "</td>";
								echo '<td class="newday"><input type="checkbox"  name="TimeSlot1" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot2" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot3" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot4" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot5" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot6" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot7" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot8" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								//echo "</tr>";
								$DayCount++;
						}
						
					}
					else
					{
						//no data at all, fill with empty table
						$DayCount = 0;
						$DayID = 7;
						if($MemberID > 0)
						 {
							 //Update remaining Days with no data
							for ($x = $DayCount; $x < 7; $x++) {
								//echo "<td>" . $Availability->GetDayShort($DayCount) . "</td>";
								echo '<td class="newday"><input type="checkbox"  name="TimeSlot1" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot2" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot3" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot4" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot5" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot6" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot7" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								echo '<td><input type="checkbox"  name="TimeSlot8" id="' . $DayCount . '" value="' . $MemberID . '"/></td>';
								//echo "</tr>";
								$DayCount++;
							}
						 }
						
						
					}
					$stmt->close();		
				}
			}
		
		?>
			
			
					
			
			</table>
		
		<div id="results"></div>
	</div>
<script>

		function UpdateAvailability(input){
				
				if(input.checked){
					$.ajax({
					  type: "POST",
					  url: "update.php",
					  data: { day: input.id, slot: input.name, value: 1, week: <? echo $week; ?>, MemberID: input.value }
					}).done(function(response){$("#results").text(response);});
				}
				else{
					$.ajax({
					  type: "POST",
					  url: "update.php",
					  data: { day: input.id, slot: input.name, value: 0, week: <? echo $week; ?>, MemberID: input.value  }
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


