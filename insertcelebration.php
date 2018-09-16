<?if(!isset($_SESSION))
	{
		session_start();
	}
	include("p_db.php");
	check_logged_in();
	$db_passwd="apmsetup";
?>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr"/>
<meta charset="UTF-8">
<?
	
	$title = $_POST['title'];
	$date = $_POST['date'];
	$exp = $_POST['exp'];
	$id = session_id();

	$connect = mysql_connect("localhost","root",$db_passwd);
	mysql_select_db('planner', $connect);
 
	$sql = "insert into Celebration(title, date, exp, id)";
	$sql .="values( '$title', '$date', '$exp', '$id')";

	$result = mysql_query($sql);
	mysql_close($connect)
?>