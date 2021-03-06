<?php
$ini_array = parse_ini_file("/var/www/ini/ef_value.ini");

$DB_HOST = $ini_array['DB_HOST'];
$DB_NAME = $ini_array['DB_NAME'];
$DB_USER = $ini_array['DB_USER'];
$DB_PASSWORD = $ini_array['DB_PASSWORD'];

if(!isset($_POST['clan_name']) || !isset($_POST['chara_name']) || !isset($_POST['channel']) ){
	echo "paramerter error";
	exit;
}


$clan_name = htmlspecialchars($_POST['clan_name']);
$chara_name = htmlspecialchars($_POST['chara_name']);
$channel = htmlspecialchars($_POST['channel']);
$power = htmlspecialchars($_POST['power']);
$level = htmlspecialchars($_POST['level']);
$ident = htmlspecialchars($_POST['ident']);

$pt_type = "ptm";

if(isset($_POST['pt_type'])){
	$pt_type=$_POST['pt_type'];
}

if ($pt_type == "ptl") {
	$is_ptl = 1;
} else {
  	$is_ptl = 0;
}

if ($ident == "") {
	$ident = "unknown";
}


$options =array();// array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

$dbh = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASSWORD, $options);
$stmt = $dbh->prepare("SELECT channel FROM enemy_party WHERE clan_name=:cln AND chara_name=:cn");
$stmt->bindparam(":cln",$clan_name);
$stmt->bindparam(":cn",$chara_name);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt=null;
if (is_array($result)) {
        if($result['channel'] != $channel) {
                $stmt = $dbh->prepare("UPDATE enemy_party SET channel=:ch, power=:pw, level=:lv, is_ptl=:ptl,mtime=now() ,ptime=now(), dead_time=NULL, ident=:sn WHERE clan_name=:cln AND chara_name=:cn");
                $stmt->bindparam(":cln",$clan_name);
                $stmt->bindparam(":cn",$chara_name);
                $stmt->bindparam(":ch",$channel);
                $stmt->bindparam(":pw",$power);
                $stmt->bindparam(":lv",$level);
                $stmt->bindparam(":ptl",$is_ptl);
		$stmt->bindparam(":sn",$ident);
        } else {
                $stmt = $dbh->prepare("UPDATE enemy_party SET mtime=now(),power=:pw, level=:lv, is_ptl=:ptl, ident=:sn WHERE clan_name=:cln AND chara_name=:cn");
                $stmt->bindparam(":cln",$clan_name);
                $stmt->bindparam(":cn",$chara_name);
                $stmt->bindparam(":pw",$power);
                $stmt->bindparam(":lv",$level);
                $stmt->bindparam(":ptl",$is_ptl);
		$stmt->bindparam(":sn",$ident);
        }
} else {
        $stmt = $dbh->prepare("INSERT INTO enemy_party(clan_name,chara_name,channel,power,level,is_ptl,ctime,mtime,ptime,ident) VALUES(:cln,:cn,:ch,:pw,:lv,:ptl,now(),now(),now(),:sn)");
        $stmt->bindparam(":cln",$clan_name);
        $stmt->bindparam(":cn",$chara_name);
        $stmt->bindparam(":ch",$channel);
        $stmt->bindparam(":pw",$power);
        $stmt->bindparam(":lv",$level);
        $stmt->bindparam(":ptl",$is_ptl);
	$stmt->bindparam(":sn",$ident);
}

$stmt->execute();

$stmt = $dbh->prepare("INSERT INTO sensor_status(sensor_name,mtime) VALUES(:sn,now()) ON DUPLICATE KEY UPDATE mtime=now()");
$stmt->bindparam(":sn",$ident);
$stmt->execute();


echo "added";
