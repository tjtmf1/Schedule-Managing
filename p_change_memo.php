<?if(!isset($_SESSION))session_start();
include("p_db.php");
check_logged_in();
$id=$_SESSION['id'];
foreach ($_POST as $key => $memo) {
	change_memo($key,$_POST,$id);
}

?>