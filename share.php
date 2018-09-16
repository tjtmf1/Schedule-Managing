<?
	$db_passwd = "apmsetup";
	if(!isset($_SESSION))
	{
		session_start();
	}

	$mydb = mysql_connect("localhost", "root", $db_passwd);
	mysql_select_db("planner", $mydb);
	$date = date("Y-m-d");
	$query = "insert into group_notice (group_name, content, date, kind) values('".$_POST["group"]."', '".$_SESSION["id"]."/".$_POST["num"]."', '$date', '".$_POST["kind"]."')";
	if(mysql_query($query))
	{
		?>
		<script>
		window.close();
		</script>
		<?
	}
?>