<?php
aa
$ini_array = parse_ini_file("/var/www/ini/ef_value.ini");

$DB_HOST = $ini_array['DB_HOST'];
$DB_NAME = $ini_array['DB_NAME'];
$DB_USER = $ini_array['DB_USER'];
$DB_PASSWORD = $ini_array['DB_PASSWORD'];

if(!isset($_POST['id'])){
	echo "ERROR";
	exit;
}
$options =array();// array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

$dbh = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASSWORD, $options);
$stmt = $dbh->prepare("SELECT dead_time FROM enemy_party WHERE ID=:id");
$stmt->bindparam(":id",$_POST['id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result['dead_time'] == "") {
	$stmt = $dbh->prepare("UPDATE enemy_party SET dead_time=NOW() WHERE ID=:id");
	$stmt->bindparam(":id",$_POST['id']);
	$stmt->execute();
} else {
	$stmt = $dbh->prepare("UPDATE enemy_party SET dead_time=NULL WHERE ID=:id");
	$stmt->bindparam(":id",$_POST['id']);
	$stmt->execute();
}
echo "ok"
?>
