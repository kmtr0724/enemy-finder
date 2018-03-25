<?php
$ini_array = parse_ini_file("/var/www/ini/ef_value.ini");

$DB_HOST = $ini_array['DB_HOST'];
$DB_NAME = $ini_array['DB_NAME'];
$DB_USER = $ini_array['DB_USER'];
$DB_PASSWORD = $ini_array['DB_PASSWORD'];

if(!isset($_POST['id']) || !isset($_POST['comment'])){
	exit;
}

$comment = htmlspecialchars($_POST['comment']);
$options =array();// array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

$dbh = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASSWORD, $options);
$stmt = $dbh->prepare("UPDATE enemy_party SET comment=:cmt WHERE ID=:id");
$stmt->bindparam(":id",$_POST['id']);
$stmt->bindparam(":cmt",$comment);
$stmt->execute();
echo "ok"
?>
