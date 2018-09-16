<?if(!isset($_SESSION))session_start();
include("p_db.php");
check_logged_in();
$id=$_SESSION['id'];
foreach($_POST as $key => $friend){
	if($key=='group')
	{
		
	}
	else
	{
		var_dump($_POST);
		$num+=invite_mem($friend,$id,$_POST['group']);
	}
}
$_SESSION['alram']=true;
?>
<meta http-equiv="refresh" content="0; p_invite_member.html?num<?=$num?>&group=<?=$_POST['group']?>">

