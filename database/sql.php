<?php

$db = 0;

function DBconnect() {
    global $dbserver, $dblogin, $dbpasswd, $dbname, $db;
    $dsn = 'mysql:host=' . $dbserver . ';dbname=' . $dbname;
    $db = new PDO($dsn, $dblogin, $dbpasswd);
    return true;
}

function DBgetCurrentVersion() {
	global $db;
	$sql = "SELECT version from scheme_version where name='db'";
	$row = $db->query($sql)->fetch();
	return $row['version']; 
}

function DBsetCurrentVersion($version) {
	global $db;
	$db->exec("UPDATE scheme_version SET version=" . $version . " WHERE name='db'");
}

function DBgetSaltAndHash($login) {
    global $db;
    $retval[0] = "";
    $retval[1] = "";

    if (!$db) return $retval;
    if (empty($login)) return $retval;

    if (!$stmt = $db->prepare("SELECT pwdSalt,pwdHash FROM users WHERE login=(:login)")) {
        echo "Prepare failed";
        return $retval;
    };

    if (!$stmt->bindValue(":login", $login, PDO::PARAM_STR)) {
        echo "Binding parameters failed";
        return $retval;
    }

    if (!$stmt->execute()) {
        echo "Execute failed";
    }

    $row =$stmt->fetch(PDO::FETCH_NUM);
    if (!$row) return $retval;
    $retval[0] = $row[0];
    $retval[1] = $row[1];

    return $retval;
}

function DBgetUserList() {
    global $db;
    $users = array();
    $sql = 'SELECT login, firstname, surname, email, permissions, flags FROM users ORDER BY id';
    foreach ($db->query($sql) as $row) {

        $usr = array();
        $usr['login']       = $row['login'];
        $usr['firstname']   = $row['firstname'];
        $usr['surname']     = $row['surname'];
        $usr['email']       = $row['email'];
        $usr['permissions'] = $row['permissions'];
        $usr['flags']       = $row['flags'];

        array_push($users, $usr);
    }
    return $users;
}

function DBaddUser($user) {
    global $db;

    if (!$stmt = $db->prepare("INSERT INTO users SET login=:login, firstname=:firstname, surname=:surname, email=:email, pwdHash=:pwdHash, pwdSalt=:pwdSalt, permissions=:permissions, flags=:flags")) {
        echo "Prepare failed";
        return false;
    };

    $stmt->bindValue(":login",       $user['login']      , PDO::PARAM_STR);
    $stmt->bindValue(":firstname",   $user['firstname']  , PDO::PARAM_STR);
    $stmt->bindValue(":surname",     $user['surname']    , PDO::PARAM_STR);
    $stmt->bindValue(":email",       $user['email']      , PDO::PARAM_STR);
    $stmt->bindValue(":pwdHash",     $user['pwdHash']    , PDO::PARAM_STR);
    $stmt->bindValue(":pwdSalt",     $user['pwdSalt']    , PDO::PARAM_STR);
    $stmt->bindValue(":permissions", $user['permissions'], PDO::PARAM_INT);
    $stmt->bindValue(":flags",       $user['flags']      , PDO::PARAM_INT);

    if (!$stmt->execute()) {
        echo "Execute failed";
        return false;
    }

    return true;
}
