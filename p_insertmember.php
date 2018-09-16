<?if(!isset($_SESSION))session_start();
include("p_db.php");
check_logged_in();
$id=$_SESSION['id'];
foreach($_POST as $key => $insert)
{
	insert_mem($id,$_POST['insert']);
}
?>
<meta http-equiv="refresh" content="5; p_group.html">

