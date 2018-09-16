<?if(!isset($_SESSION))session_start();
include("p_db.php");
check_logged_in();
$id=$_SESSION['id'];
$db_passwd ='apmsetup';
mysql_connect('localhost','root',$db_passwd);
mysql_select_db('planner');
$query=mysql_query("select * from member where id='".$_POST['friend']."'");
foreach($_POST as $key => $friend){
	if(mysql_num_rows($query)!=0){
		$query2=mysql_query("select * from friend where id='".$_POST['friend']."'and friend='".$id."'");
		if(mysql_num_rows($query2)==0)
		{
			apply_friend($friend,$id);
		}
		else
		{
			$a = mysql_fetch_array($query2);
			if($a['accept']==true)
				$_SESSION['notice']=$_POST['friend']."님과 이미 친구입니다.";
			else
				$_SESSION['notice']="이미 친구신청을 보냈습니다.";
		}
	}	
	else
	$_SESSION['notice']="해당 id가 존재하지 않습니다.";

}

?>
<script>
	history.back();
</script>