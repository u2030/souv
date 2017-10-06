<?php
echo "start:";

$mysqli = mysqli_connect("http://mysql-mysqldb.193b.starter-ca-central-1.openshiftapps.com","user5FT","EA2pDmbVOksIvxID","sampledb",3306);

var_dump($mysqli);

/*

$dbhost = getenv('OPENSHIFT_MYSQL_DB_HOST'); // Host name 
$dbport = getenv('OPENSHIFT_MYSQL_DB_PORT'); // Host port
$dbusername = getenv('OPENSHIFT_MYSQL_DB_USERNAME'); // MySQL username 
$dbpassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD'); // MySQL password 
$db_name = getenv('OPENSHIFT_GEAR_NAME'); // Database name 


echo $dbhost . ":" . $dbport;
echo $db_name . " " . $dbusername;

$mysqlCon = mysqli_connect(getenv('OPENSHIFT_MYSQL_DB_HOST'), getenv('OPENSHIFT_MYSQL_DB_USERNAME'), getenv('OPENSHIFT_MYSQL_DB_PASSWORD'), "", getenv('OPENSHIFT_MYSQL_DB_PORT')) or die("Error: " . mysqli_error($mysqlCon));

mysqli_select_db($mysqlCon, getenv('OPENSHIFT_APP_NAME')) or die("Error: " . mysqli_error($mysqlCon));

var_dump($mysqlCon);
*/
echo "end";

?>
