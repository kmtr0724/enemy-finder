<?php

$options =array();// array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, $options);
$stmt = $dbh->prepare("SELECT * FROM enemy_party WHERE mtime > (now() - INTERVAL 30 minute ) ORDER BY  mtime DESC;");

$stmt->execute();

$result = $stmt->fetchall(PDO::FETCH_ASSOC);
$class="";

?>
<table>
<tr><th>操作</th><th>血盟</th><th>キャラ名</th><th>チャンネル</th><th>確認時刻</th><th>チャンネル滞在時間(分)</th><th>死亡時刻</th></tr>
<?php foreach($result as $line):?>
<?php $class = $line['dead_time']=="" ? "": "dead_class";?>
<tr class=<?php echo"\"$class\""?>>
<td><button type="button" class="pt_check_class" data-enemy-id=<?php echo $line['ID']?>>済</button></td>
<td><?php echo $line['clan_name']?></td>
<td><?php echo $line['chara_name']?></td>
<td align="center"><?php echo $line['channel']?></td>
<td><?php echo $line['mtime']?></td>
<td align="center"><?php echo round((strtotime($line['mtime'])-strtotime($line['ptime']))/60,0) ?></td>
<td><?php echo $line['dead_time']?></td>
<?php endforeach?>
</table>
