<?php
$ini_array = parse_ini_file("/var/www/ini/ef_value.ini");

$DB_HOST = $ini_array['DB_HOST'];
$DB_NAME = $ini_array['DB_NAME'];
$DB_USER = $ini_array['DB_USER'];
$DB_PASSWORD = $ini_array['DB_PASSWORD'];

$options =array();// array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

$dbh = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASSWORD, $options);
$stmt = $dbh->prepare("SELECT * FROM sensor_status WHERE mtime > (now() - INTERVAL 30 minute )");
$stmt->execute();

$result = $stmt->fetchall(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Sensor Status</title>
</head>
<body>
<h1>Status</h1>
<table>
<tr><th>name</th><th>mtime</th></tr>
<?php foreach($result as $line):?>
<td><?php echo $line['sensor_name']?></td>
<td><?php echo date("m/d H:i:s",strtotime($line['mtime']))?></td>
<?php endforeach?>
</table>
</body>
</html>
