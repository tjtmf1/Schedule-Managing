<?if(!isset($_SESSION))session_start();
include("p_db.php");
check_logged_in();
$id=$_SESSION['id'];
mysql_connect('localhost','root',$db_passwd);
mysql_select_db('planner');
$query=mysql_query("select * from member where id='".$_POST['friend']."'");
if(mysql_num_rows($query)!=0){
	add_friend($_POST['friend'],$id);
}
?>
<meta http-equiv="refresh" content="0; URL=p_setfriend.html">