<?php
require '../config.php';

if(!$service_status){ //总开关，定义在config.php
	die($errmsg[1]['msg']);
}

$conn = mysqli_connect($mysql_server_host, $mysql_username, $mysql_password, $mysql_database);
if(!$conn){//连接数据库
	die($errmsg[2]['msg']);
}

$time = date('Y-m-d H:i:s');
$sql = "update {$mysql_prefix}tokens set online=0 where DATE_ADD(lastrequest,INTERVAL 1 MINUTE)<\"$time\"";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}

$sql = "delete from {$mysql_prefix}data where DATE_ADD(time,INTERVAL 30 DAY)<\"$time\"";
$query_res = query_sql($sql);
if(!$query_res){
	die($errmsg[4]['msg']);
}

echo json_encode(array("result"=>0,"msg"=>"OK"));

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