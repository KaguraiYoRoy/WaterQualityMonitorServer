<?php
require '../config.php';

if(!$service_status){
	echo json_encode($errmsg[1]);
	return;
}

if(!isset($_REQUEST['token'])){
	die(json_encode($errmsg[3]));
}

$conn = mysqli_connect($mysql_server_host, $mysql_username, $mysql_password, $mysql_database);
if(!$conn){
	die(json_encode($errmsg[2]));
}

$token = $_REQUEST['token'];
$batvoltage = filter(isset($_REQUEST['batvoltage'])?$_REQUEST['batvoltage']:0.00);
$sql = "select id from {$mysql_prefix}tokens where token=\"" . filter($token) . "\"";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}
if(!$row = mysqli_fetch_array($query_res)){
	die(json_encode($errmsg[5]));
}
$id = $row['id'];

$time = date('Y-m-d H:i:s');
$sql = "update {$mysql_prefix}tokens set online=1,batvoltage=$batvoltage,lastrequest=\"$time\" where id=$id";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}

$sql = "select task from {$mysql_prefix}tasks where id=$id";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}
$tasks = array();
while($row = mysqli_fetch_array($query_res))
	array_push($tasks,json_decode($row['task']));

$sql = "delete from {$mysql_prefix}tasks where id=$id";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}

echo(json_encode(array('result'=>0,'msg'=>'OK','tasks'=>$tasks)));

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