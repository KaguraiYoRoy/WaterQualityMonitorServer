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
$sql = "select id,nick from tokens where token=\"$token\"";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}
if(!$row = mysqli_fetch_array($query_res)){
	header('Refresh:3;url=login.html');
	die("Login Failed!");
}
$id = $row['id'];
$nick = $row['nick'];
$time = date('Y-m-d');

$filename = "$id.$nick-DataTable-$time.csv";

$sql = "select * from data where id=$id order by time desc";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}

header("Content-Type: application/txt");
header("Content-Disposition: attachment; filename=".$filename);

echo "Time,Core Temp,Water Temp,TDS,PH,Turbidity".PHP_EOL;

while($row = mysqli_fetch_array($query_res)){
	echo $row['time'].','.$row['lm35'].','.$row['watertemp'].','.$row['tds'].','.$row['ph'].','.$row['turbidity'].PHP_EOL;
}


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