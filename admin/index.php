<?php
require '../config.php';

if(!$service_status){ //总开关，定义在config.php
	die($errmsg[1]['msg']);
}

$conn = mysqli_connect($mysql_server_host, $mysql_username, $mysql_password, $mysql_database);
if(!$conn){//连接数据库
	die($errmsg[2]['msg']);
}

if(!isset($_COOKIE['token'])){
	header('Refresh:3;url=login.html');
	die("You haven't login yet!");
}

$token = $_COOKIE['token'];
$sql = "select id from {$mysql_prefix}tokens where token=\"$token\"";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}
if(!$row = mysqli_fetch_array($query_res)){
	header('Refresh:3;url=login.html');
	die("Login Failed!");
}
$id = $row['id'];

$sql = "select nick,online,batvoltage from {$mysql_prefix}tokens where id=$id";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}
if(!$row = mysqli_fetch_array($query_res)){
	$nick = "获取设备代号失败！";
	$online = "获取在线状态失败！";
	$batvoltage = "获取电池信息失败！";
}
else {
	$nick = $row['nick'];
	$online = $row['online'];
	$batvoltage = $row['batvoltage'];
}

?>
<html>
<head>
	<meta charset="utf-8">
	<title>WaterMonitor Login</title>
	<script>
	</script>
</head>
<body>
<table>
	<tr>
		<th>机器代号：</th>
		<th><?php echo $nick;?></th>
	</tr>
	<tr>
		<th>在线状态：</th>
		<th><?php echo $online?"在线":"离线"?></th>
	</tr>
	<tr>
		<th>电池电压：</th>
		<th><?php echo $batvoltage;?>
	</tr>
</table>
<hr>
<?php

$sql = "select count(*) from {$mysql_prefix}data where id = $id";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}
$datarows = 0;
if($row = mysqli_fetch_array($query_res)){
	$datarows = $row[0];
}


$pages = floor(($datarows - 1) /50 + 1);
$cur_page = isset($_REQUEST['page'])?$_REQUEST['page']:1;

$start = 50 * ($cur_page - 1);

$sql = "select * from {$mysql_prefix}data where id=$id order by time desc limit $start,50";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}

?>

<table>
	<tr>
		<th>Time</th>
		<th>Core Temp</th>
		<th>Water Temp</th>
		<th>TDS</th>
		<th>PH</th>
		<th>Turbidity</th>
	</tr>

<?php

while($row = mysqli_fetch_array($query_res)){

	echo "<th>" . $row['time'] . "</th>";
	echo "<th>" . $row['lm35'] . "</th>";
	echo "<th>" . $row['watertemp'] . "</th>";
	echo "<th>" . $row['tds'] . "</th>";
	echo "<th>" . $row['ph'] . "</th>";
	echo "<th>" . $row['turbidity'] . "</th>";
	echo "</tr>";

}

?>
</table>
<h2>Data Table</h2>
<?php
echo "Total: $datarows rows/$pages pages.<br>";
?>
<a href="./exportcsv.php"><input type="button" value="导出"/></a>
<table><tr>
<?php

if($cur_page > 1){
	$lastpage = $cur_page - 1;
	echo "<th><form action=\"index.php?page=1\" method=\"post\"><input type=\"submit\" value=\"<<\"></form></th>";
	echo "<th><form action=\"index.php?page=$lastpage\" method=\"post\"><input type=\"submit\" value=\"<\"></form></th>";
}

if($pages != 1){
	echo "
		<th>
		<form action=\"index.php\" method=\"post\">
			<input type=\"number\" name=\"page\" style=\"width:40px\" value=$cur_page>
			<input type=\"submit\" value=\"Jump to\">
		</form>
		</th>
	";
}

if($cur_page < $pages){
	$nextpage = $cur_page + 1;
	echo "<th><form action=\"index.php?page=$nextpage\" method=\"post\"><input type=\"submit\" value=\">\"></form></th>";
	echo "<th><form action=\"index.php?page=$pages\" method=\"post\"><input type=\"submit\" value=\">>\"></form></th>";
}


?>
</tr></table>
<hr>
<form action="login.php?logout=1" method="post">
	<input type="submit" value="注销">
</form>
</body>
<footer>
	<p>Copyright. ©2020-2023 iYoRoy Studio.
</footer>
</html>

<?php

cleanup();

function cleanup(){
	global $conn;
	mysqli_close($conn);
}

function query_sql($sql){
	global $conn;
	$query_res = mysqli_query($conn,$sql);
	return $query_res;
}

?>