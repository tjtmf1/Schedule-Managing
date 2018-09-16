<?
	if(!isset($_SESSION))
		session_start();
	include("p_db.php");
	if(check_passwd($_POST['id'],$_POST['passwd'],$name))
	{
		$_SESSION['id']=$_POST["id"];
		$_SESSION['passwd']=$_POST["passwd"];
		$_SESSION['flaash']="$name 님이 접속하였습니다.";
		?>
			<meta http-equiv="refresh" content="0; url=main.html">
		<?
	}
	else
	{
		$_SESSION['flash']="로그인에 실패하였습니다.";
		?>
			<meta http-equiv="refresh" content="0; url=p_login.html">
		<?
	}
?>