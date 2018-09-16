<?
	if(!isset($_SESSION))
	{
		session_start();
	}
	session_unset();
	$_SESSION['flash'] = "정상적으로 로그아웃 되었습니다.";
	header("location:p_login.html");
?>