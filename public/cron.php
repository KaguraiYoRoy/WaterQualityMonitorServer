<?php
require '../config.php';

if(!$service_status){
	echo json_encode($errmsg[1]);
	return;
}

$conn = mysqli_connect($mysql_server_host, $mysql_username, $mysql_password, $mysql_database);
if(!$conn){
	die(json_encode($errmsg[2]));
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