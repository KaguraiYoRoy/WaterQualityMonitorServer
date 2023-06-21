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
	echo "You haven't login yet!";
	header('Refresh:3;url=login.html');
}

$token = $_COOKIE['token'];
$sql = "select id from tokens where token=\"$token\"";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}
if(!$row = mysqli_fetch_array($query_res)){
	header('Refresh:3;url=login.html');
	die("Login Failed!");
}
$id = $row['id'];

?>
<html>
<head>
	<meta charset="utf-8">
	<title>WaterMonitor Login</title>
</head>
<body>
<hr>
<?php

$sql = "select count(*) from data where id = $id";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}
$datarows = 0;
if($row = mysqli_fetch_array($query_res)){
	$datarows = $row[0];
}

echo "Total: $datarows rows.<br>";

$pages = ($datarows - 1) /50 + 1;
$cur_page = isset($_REQUEST['page'])?$_REQUEST['page']:1;

$start = 50 * ($cur_page - 1);

$sql = "select * from data where id=$id limit $start,50";
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

	echo "<tr>";
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