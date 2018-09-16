<?if(!isset($_SESSION))
	{
		session_start();
	}	
	$db_passwd = "apmsetup";
	include("p_db.php");
	check_logged_in();
	if($_POST["schedule"] && $_POST["importance"] && $_POST["start"] && $_POST["end"] && $_POST["place"] && $_POST["explain"])
	{
		$mydb = mysql_connect("localhost", "root", "$db_passwd");
		mysql_select_db("planner", $mydb);
		$query = "update schedule set schedule = '".$_POST["schedule"]."' where schedule = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update schedule set importance = '".$_POST["importance"]."' where schedule = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);	
		$query = "update schedule set end = '".$_POST["end"]."' where schedule = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update schedule set place = '".$_POST["place"]."' where schedule = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update schedule set exp = '".$_POST["explain"]."' where schedule = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update schedule set start = '".$_POST["start"]."' where schedule = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		?>
		<script>
		opener.location.href = opener.location.href;
		window.close();
		</script>

		<?
	}
	else
	{
		?>
		<script>
			history.back();
		</script>
		<?
	}
?>