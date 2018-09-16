<?if(!isset($_SESSION))session_start();
include("p_db.php");
check_logged_in();
$id=$_SESSION['id'];

foreach ($_POST as $key => $f) {
	delete_friend($f,$id);
}
?>
<meta http-equiv="refresh" content="0.3; URL=p_setfriend.html">