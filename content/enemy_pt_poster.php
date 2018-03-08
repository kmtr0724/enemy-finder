<?php

if(!isset($_POST['clan_name']) || !isset($_POST['chara_name']) || !isset($_POST['channel']) ){
	echo "paramerter error";
	exit;
}
$clan_name = htmlspecialchars($_POST['clan_name']);
$chara_name = htmlspecialchars($_POST['chara_name']);
$channel = htmlspecialchars($_POST['channel']);

$options =array();// array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, $options);
$stmt = $dbh->prepare("SELECT channel FROM enemy_party WHERE clan_name=:cln AND chara_name=:cn");
$stmt->bindparam(":cln",$clan_name);
$stmt->bindparam(":cn",$chara_name);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt=null;
if (is_array($result)) {
        if($result['channel'] != $channel) {
                $stmt = $dbh->prepare("UPDATE enemy_party SET channel=:ch,mtime=now() ,ptime=now(), dead_time=NULL WHERE clan_name=:cln AND chara_name=:cn");
                $stmt->bindparam(":cln",$clan_name);
                $stmt->bindparam(":cn",$chara_name);
                $stmt->bindparam(":ch",$channel);
        } else {
                $stmt = $dbh->prepare("UPDATE enemy_party SET mtime=now() WHERE clan_name=:cln AND chara_name=:cn");
                $stmt->bindparam(":cln",$clan_name);
                $stmt->bindparam(":cn",$chara_name);
        }
} else {
        $stmt = $dbh->prepare("INSERT INTO enemy_party(clan_name,chara_name,channel,ctime,mtime,ptime) VALUES(:cln,:cn,:ch,now(),now(),now())");
        $stmt->bindparam(":cln",$clan_name);
        $stmt->bindparam(":cn",$chara_name);
        $stmt->bindparam(":ch",$channel);
}

$stmt->execute();


echo "added";
