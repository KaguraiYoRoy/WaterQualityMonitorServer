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

$code = $_REQUEST['code'];
$nick = $_REQUEST['nick'];

$sql = "select id from activation_code where code=\"" . filter($code) . "\"";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}
if(!$row = mysqli_fetch_array($query_res)){
	die(json_encode($errmsg[5]));
}
$id = $row['id'];

$sql = "delete from tokens where id=$id";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[2]));
}

$token = md5($id . $_REQUEST['nick'] . time() . rand(1,1024));

$sql = "insert into tokens values($id,\"$token\",\"" . filter($nick) . "\")";
$query_res = query_sql($sql);
if(!$query_res){
	die(json_encode($errmsg[4]));
}

echo(json_encode(array('result'=>0,'msg'=>'OK','token'=>$token)));

cleanup();

function cleanup(){
	global $conn;
	mysqli_close($conn);
}

function filter($str)
{
    if (empty($str)) return false;
    $str = htmlspecialchars($str);
    $str = str_replace( '/', "", $str);
    $str = str_replace( '"', "", $str);
    $str = str_replace( '(', "", $str);
    $str = str_replace( ')', "", $str);
    $str = str_replace( 'CR', "", $str);
    $str = str_replace( 'ASCII', "", $str);
    $str = str_replace( 'ASCII 0x0d', "", $str);
    $str = str_replace( 'LF', "", $str);
    $str = str_replace( 'ASCII 0x0a', "", $str);
    $str = str_replace( ',', "", $str);
    $str = str_replace( '%', "", $str);
    $str = str_replace( ';', "", $str);
    $str = str_replace( 'eval', "", $str);
    $str = str_replace( 'open', "", $str);
    $str = str_replace( 'sysopen', "", $str);
    $str = str_replace( 'system', "", $str);
    $str = str_replace( '$', "", $str);
    $str = str_replace( "'", "", $str);
    $str = str_replace( "'", "", $str);
    $str = str_replace( 'ASCII 0x08', "", $str);
    $str = str_replace( '"', "", $str);
    $str = str_replace( '"', "", $str);
    $str = str_replace("", "", $str);
    $str = str_replace("&gt", "", $str);
    $str = str_replace("&lt", "", $str);
    $str = str_replace("<SCRIPT>", "", $str);
    $str = str_replace("</SCRIPT>", "", $str);
    $str = str_replace("<script>", "", $str);
    $str = str_replace("</script>", "", $str);
    $str = str_replace("select","",$str);
    $str = str_replace("join","",$str);
    $str = str_replace("union","",$str);
    $str = str_replace("where","",$str);
    $str = str_replace("insert","",$str);
    $str = str_replace("delete","",$str);
    $str = str_replace("update","",$str);
    $str = str_replace("like","",$str);
    $str = str_replace("drop","",$str);
    $str = str_replace("DROP","",$str);
    $str = str_replace("create","",$str);
    $str = str_replace("modify","",$str);
    $str = str_replace("rename","",$str);
    $str = str_replace("alter","",$str);
    $str = str_replace("cas","",$str);
    $str = str_replace("&","",$str);
    $str = str_replace(">","",$str);
    $str = str_replace("<","",$str);
    $str = str_replace(" ",chr(32),$str);
    $str = str_replace(" ",chr(9),$str);
    $str = str_replace("    ",chr(9),$str);
    $str = str_replace("&",chr(34),$str);
    $str = str_replace("'",chr(39),$str);
    $str = str_replace("<br />",chr(13),$str);
    $str = str_replace("''","'",$str);
    $str = str_replace("css","'",$str);
    $str = str_replace("CSS","'",$str);
    $str = str_replace("<!--","",$str);
    $str = str_replace("convert","",$str);
    $str = str_replace("md5","",$str);
    $str = str_replace("passwd","",$str);
    $str = str_replace("password","",$str);
    $str = str_replace("../","",$str);
    $str = str_replace("./","",$str);
    $str = str_replace("Array","",$str);
    $str = str_replace("or 1='1'","",$str);
    $str = str_replace(";set|set&set;","",$str);
    $str = str_replace("`set|set&set`","",$str);
    $str = str_replace("--","",$str);
    $str = str_replace("OR","",$str);
    $str = str_replace('"',"",$str);
    $str = str_replace("*","",$str);
    $str = str_replace("-","",$str);
    $str = str_replace("+","",$str);
    $str = str_replace("/","",$str);
    $str = str_replace("=","",$str);
    $str = str_replace("'/","",$str);
    $str = str_replace("-- ","",$str);
    $str = str_replace(" -- ","",$str);
    $str = str_replace(" --","",$str);
    $str = str_replace("(","",$str);
    $str = str_replace(")","",$str);
    $str = str_replace("{","",$str);
    $str = str_replace("}","",$str);
    $str = str_replace(".","",$str);
    $str = str_replace("response","",$str);
    $str = str_replace("write","",$str);
    $str = str_replace("|","",$str);
    $str = str_replace("`","",$str);
    $str = str_replace(";","",$str);
    $str = str_replace("etc","",$str);
    $str = str_replace("root","",$str);
    $str = str_replace("//","",$str);
    $str = str_replace("!=","",$str);
    $str = str_replace("$","",$str);
    $str = str_replace("&","",$str);
    $str = str_replace("&&","",$str);
    $str = str_replace("==","",$str);
    $str = str_replace("#","",$str);
    $str = str_replace("@","",$str);
    $str = str_replace("mailto:","",$str);
    $str = str_replace("CHAR","",$str);
    $str = str_replace("char","",$str);
    return $str;
}

function query_sql($sql){
	global $conn;
	$query_res = mysqli_query($conn,$sql);
	return $query_res;
}

?>