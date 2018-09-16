<?if(!isset($_SESSION))
	{
		session_start();
	}	
	include("p_db.php");
	check_logged_in();
	$db_passwd='apmsetup';
?>
<meta http-equiv="Content-Type" content="text/html;"/>
<meta charset="UTF-8">
<?
	$schedule = $_POST['schedule'];
	$imp = $_POST['star'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$place =$_POST['place'];
	$exp = $_POST['exp'];
	$id = $_SESSION["id"];

	if($schedule != NULL)
	{
		if(eregi("[0-9]{4}-[0-9]{2}-[0-9]{2}", $start) && eregi("[0-9]{4}-[0-9]{2}-[0-9]{2}", $end))
		{
			$connect = mysql_connect("localhost","root",$db_passwd);
			mysql_select_db('planner', $connect);

			$sql = "insert into Schedule(schedule, importance, start, end, place, exp, id)";
			$sql .=" values( '$schedule','$imp', '$start', '$end', '$place', '$exp', '$id')";

			$result = mysql_query($sql);
			mysql_close($connect);
			?>
				<script>
				opener.location.href = opener.location.href;
				window.close();
				
				</script>
			<?
		}
		else
		{
			$_SESSION['error'] = "날짜 정보가 잘못되었습니다.";		
		}
	}
	else
	{
		$_SESSION['error'] = "스케줄 제목이 없습니다.";
	}
?>
<meta http-equiv="refresh" content="0; url=schedule.html">