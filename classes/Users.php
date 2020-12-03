<?
class Users
{
	
    /**
     * @var string UserID
     */
    private $UserID = "";
	
	/**
     * @var string AuthLevel
     */
    private $AuthLevel = "";
	
	
	
	
    
    public function __construct()
    {
		date_default_timezone_set('Australia/Sydney');
		

	}
    
	Public function CheckIfSignedIn()
	{
		if(isset($_SESSION['userid'])) 
		{ 
			return true;
		}
		else if($this->ValidRememberMeCookie())
		{
			//echo "Sign in";
			
			return true;
		}
		else
		{
			
			return false;
		}
			
	}
	
	Public function ShowAccessDenied()
	{
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />';
		echo '<title>Dapto Availability</title>';
		echo '<link href="style.css" rel="stylesheet" type="text/css" />';
		echo '</head>';
		echo '<div class="head">
		<img src="logo.2b1db366.svg" alt="SES Logo" width="20" height="20">
		Dapto Availability
		</div>
		<body><div class="h2"><p align="center">Access Denied</p>';
		echo '<div class="menuInline" align="center"><a href=index.php>Back</a><br>';
		echo "<a href=login.php>Log In</a>";
		echo "</div></body>";
		exit;
			
	}
	
	
	Public function AddUser($username, $Name, $Access, $Password)
	{
		include './tools/dbconnect.php';
		
		$options = ['cost' => 12,];
		$hash_Password = password_hash($Password, PASSWORD_BCRYPT, $options);
		
		//$sql = "INSERT INTO webusers (username, name, access, password)
		//VALUES (?, ?, ?, ?)";
		//$stmt = $db->prepare($sql);
		//$stmt->bindParam(':SQLusername', $username);
		//$stmt->bindParam(':SQLName', $Name);
		//$stmt->bindParam(':SQLAccess', $Access);
		//$stmt->bindParam(':SQLPassword', $hash_Password);
		//$stmt->execute();
		
		$sql = "INSERT INTO webusers (username, name, access, password)VALUES ('$username','$Name','$Access','$hash_Password')";
		echo $sql;
		if ($db->query($sql) === TRUE)
		{
			return true;
		}
		else
		{
			echo "Error: Writing to webusers Table. <b>Contact Admin.</b><br>" . $db->error . "<br>" . $sql;
		}
			
	}
	
	Private function WriteTokenToDB($user, $token,$expire)
    {
		include './tools/dbconnect.php';
		$stmt = $db->prepare("INSERT INTO `tblusertokens` (`IDUser`, `Token`, `Date`) VALUES (?, ?, ?)");
		$stmt->bind_param("iss", $user,$token,$expire);
		$stmt->execute();
		$stmt->close();
		return true;
	}
	
	Private function CreateCookie($user) {
		$this->clearAuthCookie();
		$token = uniqid(rand(), true);
		$cookie_expiration_time = time()+60*60*24*90;
		$expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
		$this->WriteTokenToDB($user, $token, $expiry_date);
		$cookie = $user . ':' . $token;
		$mac = hash_hmac('sha256', $cookie, "DPTSES");
		$cookie .= ':' . $mac;
		
		setcookie('rememberme', $cookie, $cookie_expiration_time);
	}
	
	public function clearAuthCookie() {
        if (isset($_COOKIE["rememberme"])) {
            setcookie("rememberme", "",time()-3600);
        }
    }
	
	
	
	Public function ValidRememberMeCookie() {
		$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
		if ($cookie) {
			list ($user, $token, $mac) = explode(':', $cookie);
			if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, "DPTSES"), $mac)) {
				return false;
			}
			$DBToken = $this->checkDBforToken($user,$token);
			if (hash_equals($DBToken, $token)) {
				//echo "login in via cookie";
				$this->DeleteDBToken($user,$DBToken);
				$this->CookieLogin($user);
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	
	
	
	Private function checkDBforToken($user,$token) {
		include './tools/dbconnect.php';
        $stmt = $db->prepare("SELECT * FROM tblusertokens WHERE `IDUser` = ? AND Token = ?");
		$stmt->bind_param("ii", $user, $token);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if($result->num_rows != 0) 
		{
			
			$DayCount = 0;
			while($row = $result->fetch_assoc()) {
				  $DBtoken = $row['Token'];
			}
		}
		return $DBtoken;
    }
	
	Private function DeleteDBToken($user,$token) {
		include './tools/dbconnect.php';
        $stmt = $db->prepare("DELETE FROM tblusertokens WHERE `IDUser` = ? AND Token = ?");
		$stmt->bind_param("ii", $user, $token);
		$stmt->execute();
		return true;
    }
	
	
	
	
	
	Public function EditUser($UserID, $username, $Name, $Access)
	{
		include './tools/dbconnect.php';
		if ($UserID != "" and $username!= "")
		{
			

			$sql = "UPDATE webusers SET username='$username',Name='$Name',access='$Access'
							WHERE ID='$UserID'";
				if ($db->query($sql) === TRUE)
				{
					return true;
				}
				else
				{
					echo "Error: Writing to webusers Table. Contact Admin" . $DB->error . "<br>" . $sql;	
				}
		}
		else
		{
			$message = "Update Failed, All Fields are Required.";
		}
	}
	
	Public function UpdatePassword($username, $Password1, $Password2)
	{
		include './tools/dbconnect.php';
		
		
		if ($Password1 != "" and $Password2 != "")
		{
			$options = ['cost' => 12,];
			$hash_Password = password_hash($Password1, PASSWORD_BCRYPT, $options);
			$sql = "UPDATE webusers SET password='$hash_Password'
							WHERE username='$username'";
				if ($db->query($sql) === TRUE)
				{
					return 1;
				}
				else
				{
					echo "Error: Writing to webusers Table. Contact Admin" . $DB->error . "<br>" . $sql;	
					return 0;
				}
		}
		else
		{
			$message = "Update Failed, All Fields are Required.";
			return 0;
		}
	}
	
	Public function CheckPassword($username, $Password)
	{
		include './tools/dbconnect.php';
			
			
		$sql = "select * from webusers where username='$username'";
		$result = $db->query($sql);
		$RowCount = $result->num_rows;
		if ($RowCount > 0)
		{
			$row = $result->fetch_assoc();
			$hash=$row["password"];
			if(password_verify($Password , $hash) === true)
			{
				$userid=$row["username"];
				$username=$row["name"];
				$authlevel=$row["access"];
				$_SESSION['userid'] = $userid;
				$_SESSION['username'] = $username;
				$_SESSION['authlevel'] = $authlevel;
				$this->CreateCookie($userid);
				return 1;
			}
			else
			{	
				return 0;
			}
		}

			
	}
	
	private function CookieLogin($username)
	{
		include './tools/dbconnect.php';
			
			
		$sql = "select * from webusers where username='$username'";
		$result = $db->query($sql);
		$RowCount = $result->num_rows;
		if ($RowCount > 0)
		{
			$row = $result->fetch_assoc();
			$userid=$row["username"];
			$username=$row["name"];
			$authlevel=$row["access"];
			$_SESSION['userid'] = $userid;
			$_SESSION['username'] = $username;
			$_SESSION['authlevel'] = $authlevel;
			$_SESSION['CookieAuth'] = "True";
			$this->CreateCookie($userid);
			return 1;
			
			
		}
		else
			{	
				return 0;
			}

			
	}
	
	
	
	
	
	
	
	
	
 

}

?>