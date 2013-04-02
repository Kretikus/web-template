<?php

require('etc/config.php');
require('database/sql.php');

function jsonReply($data)
{
    header('Content-type: application/json');
    echo json_encode($data);
}

function serverTimeInTolerance($srvTimeAsString)
{
	$servertime = intval($srvTimeAsString);
    $diff = time() - $servertime;

	return $diff >= 0 && $diff < 5;
}

$str_json = file_get_contents('php://input');
$values = json_decode($str_json, true);

if ($values['signature'] == 'getSalt') {
    $username = $values['params']['username'];
    $retval['salt']   = substr(md5(rand()), 0, 12);
    $retval['servertime'] = time();   
    if(DBconnect()) {
         $salt = DBgetSaltAndHash($username)[0];
         if (!empty($salt)) $retval['salt'] = $salt;
    }
    jsonReply($retval);
}
else if ($values['signature'] == 'login') {
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
			}
        }
    }
    $retval = array();
    $retval['result'] = $result;
    jsonReply($retval);
}
else if ($values['signature'] == 'getUserList') {
	$retval = array();
	if (DBconnect()) {
		$retval = DBgetUserList();
	}
	jsonReply($retval);
}
else if ($values['signature'] == 'addUser') {
    $retval = array();
    $retval['result'] = 0;
    if (DBconnect()) {
        $user = $values['params'];
        $user['pwdSalt'] = "";
        $user['pwdHash'] = "";
        $user['flags']   = 0;
        if (DBaddUser($user)) $retval['result'] = 1;
    }
    jsonReply($retval);
}
else {
    header('HTTP/1.0 404 Not Found');
}

?>
