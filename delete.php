<?
	if(!isset($_SESSION))
	{
		session_start();
	}
	var_dump($_GET);
	$mydb = mysql_connect("localhost", "root", "apmsetup");
	mysql_select_db("planner", $mydb);
	switch($_GET["kind"])
	{
		case 1:
		$query = "delete from schedule where id='".$_SESSION["id"]."' and num=".$_GET["num"];
		break;
		case 2:
		$query = "delete from todo where id='".$_SESSION["id"]."' and num=".$_GET["num"];
		break;
	}
	mysql_query($query);
?>
<script>
opener.location.href = opener.location.href;
window.close();
</script>