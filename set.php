<?	if(!isset($_SESSION))
	{
		session_start();
	}
	$id = $_SESSION["id"];
    $db_passwd='apmsetup';
	$connect = mysql_connect("localhost","root",$db_passwd);
    $db=mysql_select_db("planner",$connect);
    $themenum=$_POST['theme'];
    mysql_query("update member set theme='$themenum' where id ='$id'");
    $themesql= "select theme from member where id='".$id."'";
    $the=mysql_query($themesql);
    $thenum=mysql_fetch_array($the);
  	
?>
<meta http-equiv="refresh" content="0; url=setting.html">