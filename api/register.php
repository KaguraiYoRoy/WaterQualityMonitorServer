<?php
require '../config.php';

if(!$service_status){ //总开关，定义在config.php
	echo json_encode($errmsg[1]);
	return;
}

$conn = mysqli_connect($mysql_server_host, $mysql_username, $mysql_password, $mysql_database);
if(!$conn){//连接数据库
	die(json_encode($errmsg[2]));
}

if(!isset($_REQUEST['code'])||!isset($_REQUEST['nick'])){
	die(json_encode($errmsg[3]));
}

$code = filter($_REQUEST['code']);
$nick = filter($_REQUEST['nick']);

$sql = "select id from {$mysql_prefix}activation_code where code=\"$code\"";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}
if(!$row = mysqli_fetch_array($query_res)){
	die(json_encode($errmsg[5]));
}
$id = $row['id'];

$sql = "delete from {$mysql_prefix}tokens where id=$id";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[2]));
}

$token = md5($id . $_REQUEST['nick'] . time() . rand(1,1024));
$time = date('Y-m-d H:i:s'); 

$sql = "insert into {$mysql_prefix}tokens values($id,\"$token\",\"$nick\",0,0,\"$time\")";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}

echo(json_encode(array('result'=>0,'msg'=>'OK','token'=>$token,'cron'=>$cron_url,'upload'=>$upload_url)));

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