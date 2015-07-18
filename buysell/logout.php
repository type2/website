<?
session_start();
	header('HTTP/1.0 401 Unauthorized');
	echo 'You are logged out...';
	session_register("current_user");
	session_register("agent");
	session_register("agentname");
	$current_user = "";
	$agentname = "";
	session_unset();
	session_destroy();	
?>