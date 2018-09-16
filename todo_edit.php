<?
	if(!isset($_SESSION))
	{
		session_start();
	}
	$db_passwd = "apmsetup";
	include("p_db.php");
	check_logged_in();
	if($_POST["title"] && $_POST["importance"] && $_POST["start"] && $_POST["end"] && $_POST["explain"])
	{
		$mydb = mysql_connect("localhost", "root", "$db_passwd");
		mysql_select_db("planner", $mydb);
		$query = "update todo set title = '".$_POST["title"]."' where title = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update todo set importance = '".$_POST["importance"]."' where title = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update todo set end = '".$_POST["end"]."' where title = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update todo set exp = '".$_POST["explain"]."' where title = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update todo set checked = '".$_POST["check"]."' where title = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$query = "update todo set start = '".$_POST["start"]."' where title = '".$_POST["extitle"]."' and start='".$_POST["exstart"]."' and id='".$_SESSION["id"]."'";
		mysql_query($query);
		$date = date("Ymd");
		$query = "select * from todo where id='".$_SESSION["id"]."' and replace(end, '-', '') < $date";
		$result = mysql_query($query);		
		$total = mysql_num_rows($result);
		if($total != 0)
	    {
	       $query = "select * from todo where id='".$_SESSION["id"]."' and replace(end, '-', '') < $date and checked = 1";
	       $result = mysql_query($query);
	       $count = mysql_num_rows($result);
	       $rate = $count / $total;
	       $query = "update member set rate=$rate where id='".$_SESSION["id"]."'";
	       mysql_query($query);
	    }
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