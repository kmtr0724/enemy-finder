<?php
$ini_array = parse_ini_file("/var/www/ini/ef_value.ini");

$DB_HOST = $ini_array['DB_HOST'];
$DB_NAME = $ini_array['DB_NAME'];
$DB_USER = $ini_array['DB_USER'];
$DB_PASSWORD = $ini_array['DB_PASSWORD'];
$EXCLUDE_CLAN = $ini_array['EXCLUDE_CLAN'];

$options =array();// array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

$showall = 0;
if(isset($_GET['showall'])){
	$showall=$_GET['showall'];
}


$dbh = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASSWORD, $options);
if($showall == 1){
	$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE is_ptl=0 AND mtime > (now() - INTERVAL 30 minute ) AND level > 0 AND ident!='ACER1' ORDER BY  mtime DESC;");
	$stmt->execute();
	$result = $stmt->fetchall(PDO::FETCH_ASSOC);

	
	$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE is_ptl=1 AND mtime > (now() - INTERVAL 90 minute ) AND level > 0 AND ident!='ACER1' ORDER BY  mtime DESC;");
	$stmt->execute();
	$result2 = $stmt->fetchall(PDO::FETCH_ASSOC);
	
	$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE is_ptl=0 AND mtime > (now() - INTERVAL 90 minute ) AND level > 0 AND ident='ACER1' ORDER BY  mtime DESC;");
	$stmt->execute();
	$result3 = $stmt->fetchall(PDO::FETCH_ASSOC);
	
	$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE is_ptl=1 AND mtime > (now() - INTERVAL 90 minute ) AND level > 0 AND ident='ACER1' ORDER BY  mtime DESC;");
	$stmt->execute();
	$result4 = $stmt->fetchall(PDO::FETCH_ASSOC);
} else {
	$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE clan_name!=:exc AND is_ptl=0 AND mtime > (now() - INTERVAL 30 minute ) AND level > 0 AND ident!='ACER1' ORDER BY  mtime DESC;");
	$stmt->bindparam(":exc",$EXCLUDE_CLAN);
	$stmt->execute();
	$result = $stmt->fetchall(PDO::FETCH_ASSOC);
	
	$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE clan_name!=:exc AND is_ptl=1 AND mtime > (now() - INTERVAL 90 minute ) AND level > 0 AND ident!='ACER1' ORDER BY  mtime DESC;");
	$stmt->bindparam(":exc",$EXCLUDE_CLAN);
	$stmt->execute();
	$result2 = $stmt->fetchall(PDO::FETCH_ASSOC);
	
	$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE clan_name!=:exc AND is_ptl=0 AND mtime > (now() - INTERVAL 90 minute ) AND level > 0 AND ident='ACER1' ORDER BY  mtime DESC;");
	$stmt->bindparam(":exc",$EXCLUDE_CLAN);
	$stmt->execute();
	$result3 = $stmt->fetchall(PDO::FETCH_ASSOC);
	
	$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE clan_name!=:exc AND is_ptl=1 AND mtime > (now() - INTERVAL 90 minute ) AND level > 0 AND ident='ACER1' ORDER BY  mtime DESC;");
	$stmt->bindparam(":exc",$EXCLUDE_CLAN);
	$stmt->execute();
	$result4 = $stmt->fetchall(PDO::FETCH_ASSOC);
}


$class="";

?>
<h1>北風の高原でPTに所属している敵対一覧</h1>
<table>
<tr><th>操作</th><th>血盟</th><th>キャラ名</th><th>ch</th><th>レベル</th><th>戦闘力</th><th>確認時刻</th><th>滞在(分)</th><th>死亡時刻</th><th>コメント</th></tr>
<?php foreach($result as $line):?>
<?php $class = $line['dead_time']=="" ? "": "dead_class";?>
<tr class=<?php echo"\"$class\""?>>
<td class="nopadding"><button type="button" class="pt_check_class" data-enemy-id=<?php echo $line['ID']?>>済</button></td>
<td><?php echo $line['clan_name']?></td>
<td><?php echo $line['chara_name']?></td>
<td align="center"><?php echo $line['channel']?></td>
<td align="center" <?php if($line['level']<171) echo "class=\"alert_class\""?>><?php echo $line['level']?></td>
<td align="right"><?php echo number_format($line['power'])?></td>
<td><?php echo date("m/d H:i:s",strtotime($line['mtime']))?></td>
<td align="center"><?php echo round((strtotime($line['mtime'])-strtotime($line['ptime']))/60,0) ?></td>
<td><?php if($line['dead_time']!="")echo date("m/d H:i:s",strtotime($line['dead_time']))?></td>
<td><input type="text" class="comment_class" maxlength=16 data-enemy-id=<?php echo $line['ID']?> value="<?php echo $line['comment']?>"></td>
<?php endforeach?>
</table>

<hr>

<h1>北風の高原でPTに所属していない敵対一覧</h1>
<table>
<tr><th>操作</th><th>血盟</th><th>キャラ名</th><th>ch</th><th>レベル</th><th>戦闘力</th><th>確認時刻</th><th>滞在(分)</th><th>死亡時刻</th><th>コメント</th></tr>
<?php foreach($result2 as $line):?>
<?php $class = $line['dead_time']=="" ? "": "dead_class";?>
<tr class=<?php echo"\"$class\""?>>
<td class="nopadding"><button type="button" class="pt_check_class" data-enemy-id=<?php echo $line['ID']?>>済</button></td>
<td><?php echo $line['clan_name']?></td>
<td><?php echo $line['chara_name']?></td>
<td align="center"><?php echo $line['channel']?></td>
<td align="center" <?php if($line['level']<171) echo "class=\"alert_class\""?>><?php echo $line['level']?></td>
<td align="right"><?php echo number_format($line['power'])?></td>
<td><?php echo date("m/d H:i:s",strtotime($line['mtime']))?></td>
<td align="center"><?php echo round((strtotime($line['mtime'])-strtotime($line['ptime']))/60,0) ?></td>
<td><?php if($line['dead_time']!="")echo date("m/d H:i:s",strtotime($line['dead_time']))?></td>
<td><input type="text" class="comment_class" maxlength=16 data-enemy-id=<?php echo $line['ID']?> value="<?php echo $line['comment']?>"></td>
<?php endforeach?>
</table>

<hr>

<h1>時が止まった地でPTに所属している敵対一覧</h1>
<table>
<tr><th>操作</th><th>血盟</th><th>キャラ名</th><th>ch</th><th>レベル</th><th>戦闘力</th><th>確認時刻</th><th>滞在(分)</th><th>死亡時刻</th><th>コメント</th></tr>
<?php foreach($result3 as $line):?>
<?php $class = $line['dead_time']=="" ? "": "dead_class";?>
<tr class=<?php echo"\"$class\""?>>
<td class="nopadding"><button type="button" class="pt_check_class" data-enemy-id=<?php echo $line['ID']?>>済</button></td>
<td><?php echo $line['clan_name']?></td>
<td><?php echo $line['chara_name']?></td>
<td align="center"><?php echo $line['channel']?></td>
<td align="center" <?php if($line['level']<171) echo "class=\"alert_class\""?>><?php echo $line['level']?></td>
<td align="right"><?php echo number_format($line['power'])?></td>
<td><?php echo date("m/d H:i:s",strtotime($line['mtime']))?></td>
<td align="center"><?php echo round((strtotime($line['mtime'])-strtotime($line['ptime']))/60,0) ?></td>
<td><?php if($line['dead_time']!="")echo date("m/d H:i:s",strtotime($line['dead_time']))?></td>
<td><input type="text" class="comment_class" maxlength=16 data-enemy-id=<?php echo $line['ID']?> value="<?php echo $line['comment']?>"></td>
<?php endforeach?>
</table>

<hr>

<h1>時が止まった地でPTに所属していない敵対一覧</h1>
<table>
<tr><th>操作</th><th>血盟</th><th>キャラ名</th><th>ch</th><th>レベル</th><th>戦闘力</th><th>確認時刻</th><th>滞在(分)</th><th>死亡時刻</th><th>コメント</th></tr>
<?php foreach($result4 as $line):?>
<?php $class = $line['dead_time']=="" ? "": "dead_class";?>
<tr class=<?php echo"\"$class\""?>>
<td class="nopadding"><button type="button" class="pt_check_class" data-enemy-id=<?php echo $line['ID']?>>済</button></td>
<td><?php echo $line['clan_name']?></td>
<td><?php echo $line['chara_name']?></td>
<td align="center"><?php echo $line['channel']?></td>
<td align="center" <?php if($line['level']<171) echo "class=\"alert_class\""?>><?php echo $line['level']?></td>
<td align="right"><?php echo number_format($line['power'])?></td>
<td><?php echo date("m/d H:i:s",strtotime($line['mtime']))?></td>
<td align="center"><?php echo round((strtotime($line['mtime'])-strtotime($line['ptime']))/60,0) ?></td>
<td><?php if($line['dead_time']!="")echo date("m/d H:i:s",strtotime($line['dead_time']))?></td>
<td><input type="text" class="comment_class" maxlength=16 data-enemy-id=<?php echo $line['ID']?> value="<?php echo $line['comment']?>"></td>
<?php endforeach?>
</table>
