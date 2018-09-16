<?
	if(!isset($_SESSION))
	{
		session_start();
	}
	include("p_db.php");

	add_Group($_POST["gname"]);
?>
<script>
history.back();
</script>