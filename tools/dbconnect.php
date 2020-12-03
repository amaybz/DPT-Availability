<?php

include 'secrets.php';

$db = new mysqli('localhost', 'maybzcom_dptses', $DBPassword, 'maybzcom_DPTSES');

if ($db->connect_error) {
	echo "DB Failed";
    die('Connect Error (' . $db->connect_error . ') '
            . $db->connect_error);
}

?>