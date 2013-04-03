<?php
session_start();

require('etc/config.php');
require('database/sql.php');

// FLAGS
$USER_NEEDS_PWDCHANGE = 1;


function jsonReply($data)
{
    header('Content-type: application/json');
    echo json_encode($data);
}

function sendLoginDataToEmail($email, $password) {
	$file = fopen('/home/roman/test/user.data', 'a+');
	fwrite($file, "EMail: ".$email." ".$password);
	fclose($file);
}

function serverTimeInTolerance($srvTimeAsString)
{
	$servertime = intval($srvTimeAsString);
    $diff = time() - $servertime;

	return $diff >= 0 && $diff < 5;
}

$str_json = file_get_contents('php://input');
$values = json_decode($str_json, true);

$signature = $values['signature'];


/// FIRST the Rmi function that can be called without login

if ($signature == 'getSalt') {
    $username = $values['params']['username'];
    $retval['salt']   = substr(md5(rand()), 0, 12);
    $retval['servertime'] = time();   
    if(DBconnect()) {
         $salt = DBgetSaltAndHash($username)[0];
         if (!empty($salt)) $retval['salt'] = $salt;
    }
    jsonReply($retval);
}
else if ($signature == 'login') {
    $username   = $values['params']['username'];
    $password   = $values['params']['password'];
	$servertime = $values['params']['servertime'];

    $result = 0;

    if (serverTimeInTolerance($servertime) &&  DBconnect()) {
        $saltAndHash = DBgetSaltAndHash($username);
		if (!empty($saltAndHash[1])) {
			$calcedHash  = hash_hmac('sha256', $servertime, trim($saltAndHash[1]));
        	if (strcmp(trim($calcedHash), trim($password))==0) {
				$result = 1;
				$_SESSION['is_authenticated'] = 1;
			}
        }
    }
    $retval = array();
    $retval['result'] = $result;
    jsonReply($retval);
}
else {

/// RMI functions that need a login 

if ($_SESSION['is_authenticated'] != 1) {
	header('HTTP/1.0 403 Not allowed');
	exit;
}

if ($signature == 'getUserList') {
	$retval = array();
	if (DBconnect()) {
		$retval = DBgetUserList();
	}
	jsonReply($retval);
}
else if ($signature == 'addUser') {
    $retval = array();
    $retval['result'] = 0;
    if (DBconnect()) {
		$password = substr(md5(rand()), 0, 8);
        $user = $values['params'];
        $user['pwdSalt'] = substr(md5(rand()), 0, 12);
        $user['pwdHash'] = hash_hmac('sha256', $user['pwdSalt'], $password);
        $user['flags']   = $USER_NEEDS_PWDCHANGE;  
        if (DBaddUser($user)) {
			 $retval['result'] = 1;
			sendLoginDataToEmail($user['email'], $password);
		}
    }
    jsonReply($retval);
}
else {
    header('HTTP/1.0 404 Not Found');
}

}
?>

