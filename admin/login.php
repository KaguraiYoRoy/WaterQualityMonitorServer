<?php
require '../config.php';

if(!$service_status){ //总开关，定义在config.php
	die($errmsg[1]['msg']);
}

$conn = mysqli_connect($mysql_server_host, $mysql_username, $mysql_password, $mysql_database);
if(!$conn){//连接数据库
	die($errmsg[2]['msg']);
}

if(isset($_REQUEST['logout'])&&$_REQUEST['logout']==1){
	cleanup();
	setcookie("token", "", time()-3600);
	echo "Logout!";
	header('Refresh:3;url=login.html');
}

$token = filter($_REQUEST['token']);
$sql = "select id from {$mysql_prefix}tokens where token=\"$token\"";
$query_res = query_sql($sql);
if(!$query_res){
	cleanup();
	die($errmsg[4]['msg']);
}
if(!$row = mysqli_fetch_array($query_res)){
	cleanup();
	die("Login failed!<br>Please try again.");
}

$expire=time()+60*60*24*30;
setcookie("token", $token, $expire);
echo "Login Success!";
cleanup();

header('Refresh:3;url=index.php');

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