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

$token = filter($_REQUEST['token']);
$watertemp = isset($_REQUEST['WaterTemp'])?doubleval($_REQUEST['WaterTemp']):'0.00';
$tds = isset($_REQUEST['TDS'])?intval($_REQUEST['TDS']):0;
$lm35 = isset($_REQUEST['LM35'])?doubleval($_REQUEST['LM35']):'0.00';
$ph = isset($_REQUEST['PH'])?doubleval($_REQUEST['PH']):'0.00';
$turbidity = isset($_REQUEST['Turbidity'])?doubleval($_REQUEST['Turbidity']):'0.00';
$sql = "select {$mysql_prefix}id from tokens where token=\"$token\"";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}
if(!$row = mysqli_fetch_array($query_res)){
	die(json_encode($errmsg[5]));
}
$id = $row['id'];

$time = date('Y-m-d H:i:s');
$sql = "update {$mysql_prefix}tokens set online=1,lastrequest=\"$time\" where id=$id";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}

$sql = "insert into {$mysql_prefix}data values(\"$time\",$id,$watertemp,$tds,$lm35,$ph,$turbidity)";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}

echo(json_encode(array('result'=>0,'msg'=>'OK')));

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