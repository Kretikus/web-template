<?php

require('../etc/config.php');
require('sql.php');

if (!DBconnect()) {
	echo "Could not connect to DB.";
	exit;
}

$currentVersion = DBgetCurrentVersion();

$file = fopen('structure.sql', 'r');
$contents=fread($file, 10000000);

preg_match_all("/\-\- Version (\d+)/", $contents, $treffer);

$allVersions = $treffer[1];
$newDBVersion = $allVersions[ count($allVersions)-1 ];

if ($newDBVersion > $currentVersion) {
	echo "Old Database ". $currentVersion." found. Updating to database schema ". $newDBVersion . "\n";
}


?>
