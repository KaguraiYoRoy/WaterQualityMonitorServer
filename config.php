<?php
//Database config
$mysql_server_host = 'localhost';
$mysql_username = '';
$mysql_password = '';
$mysql_database = '';

$service_status = true;
$debug_mode = false;

$errmsg=array(//错误返回信息
	1=>array('result'=>1,'msg'=>'Service is unavailable'),			//登录服务已禁用
	2=>array('result'=>2,'msg'=>'Failed to connect to Database'),	//数据库连接失败
	3=>array('result'=>3,'msg'=>'Missing parameter'),				//缺少参数（见上表，若传参缺失会报此错误码）
	4=>array('result'=>4,'msg'=>'Database query Failed'),			//数据库操作失败
    5=>array('result'=>5,'msg'=>'Invalid activation code')          //无效密钥
);

?>
