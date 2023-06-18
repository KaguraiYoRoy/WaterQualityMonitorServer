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
	echo "Login Failed!";
	header('Refresh:3;url=login.html');
}

?>
<html>
<head>
	<meta charset="utf-8">
	<title>WaterMonitor Login</title>
</head>
<body>
<?php
	
?>
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