<?
	$db_passwd='apmsetup';
function check_passwd($id, $passwd, &$name)
{
	echo"$id";
	global $db_passwd;
	$connect = mysql_connect("localhost","root",$db_passwd);
	if($connect)
	{
		$db= mysql_select_db("planner",$connect);
		if($db)
		{
			$query=mysql_query("select * from member where id='".$id."' and password='".md5($passwd)."'");
			if(mysql_num_rows($query))
			{
				$arr = mysql_fetch_array($query);
				$name = $arr['name'];
				return true;
			}
			else
				return false;
		}
		else
		{
			echo"해당 database가 없습니다.";
			return false;
		}
	}
	else
	{
		echo"연결에 실패했습니다.";
		return false;
	}
}

function check_logged_in()
{
	global $db_passwd;
	if(!isset($_SESSION["id"]))
	{
		
		$_SESSION['flash']="로그인후 사용가능합니다.";
		?>
		<meta http-equiv="refresh" content="0; URL=p_login.html">
		<?
	}
}

function delete_friend($friend,$id)
{
	global $db_passwd;
	$connect = mysql_connect("localhost","root",$db_passwd);
	$db=mysql_select_db("planner",$connect);
	$query2=mysql_query("select * from friend where id='".$friend."' and friend='".$id."'");
	if(mysql_num_rows($query2))
	{
		mysql_query("delete from friend where id='".$friend."' and friend='".$id."'");
	}
	$query= mysql_query("delete from friend where id='".$id."' and friend='".$friend."'");

}

function add_friend($friend,$id)
{
	global $db_passwd;
	$connect = mysql_connect("localhost","root",$db_passwd);
	$db=mysql_select_db("planner",$connect);
	$query = mysql_query("update friend set accept=true where id='".$id."' and friend='".$friend."'");
	$query2 = mysql_query("insert into friend (id,friend, accept) values ('".$friend."','".$id."','1')");
	?>
	<meta http-equiv="refresh" content="1; URL=p_setfriend.html">
	<?
}
function apply_friend($friend,$id)
{
	global $db_passwd;
	$connect = mysql_connect("localhost","root",$db_passwd);
	$db=mysql_select_db("planner",$connect);
	$query = mysql_query("insert into friend (id,friend, accept) values ('".$friend."','".$id."','0')");
	?>
	
	<?
}

function SignUp($id, $password, $passwordcheck, $name, $year, $month, $day, &$str)
{
	global $db_passwd;
	if(!$id || !$password || !$passwordcheck || !$name)
	{
		$str = "정보를 모두 입력해주세요.";
		return false;
	}
	if($mydb = mysql_connect("localhost", "root", $db_passwd))
	{
		if(mysql_select_db("planner", $mydb))
		{
			$query = "select * from member where id = '$id'";
			$result = mysql_query($query);
			if(!mysql_num_rows($result))
			{
				if($password == $passwordcheck)
				{					$password = md5($password);
					$query = "insert into member (id, password, name, birthyear, birthmonth, birthday, rate) values('$id', '$password', '$name', $year, $month, $day, 0)";
					mysql_query($query);
					$str = "회원가입이 완료되었습니다.";
					return true;
				}
				else
				{
					$str = "비밀번호가 일치하지 않습니다.";
				}
			}
			else
			{
				$str = "이미 존재하는 아이디입니다.";
			}
		}
		else
		{
			$str = "DataBase 연결에 실패하셨습니다";
		}
	}
	else
	{
		$str = "MySql 연결에 실패하셨습니다.";
	}
	return false;
}

function isHoliday($date, &$str)
{
	global $db_passwd;
	if($mydb = mysql_connect("localhost", "root", $db_passwd))
	{
		if(mysql_select_db("holiday", $mydb))
		{
			$query = "select * from g4_lunartosolar where solar_date = '$date'";
			$result = mysql_query($query);
			$row = mysql_fetch_row($result);
			if($row[3] == true)
			{
				switch($row[4])
				{
				case "newyear":
					$str = "신정";
					break;
				case "seol":
					$str = "설날";
					break;
				case "31day":
					$str = "삼일절";
					break;						
				case "buddha":
					$str = "석가탄신일";
					break;
				case "childday":
					$str = "어린이날";
					break;			
				case "memorial":
					$str = "현충일";
					break;								
				case "independence":
					$str = "광복절";
					break;
				case "chuseok":
					$str = "추석";
					break;		
				case "foundation":
					$str = "개천절";
					break;		
				case "hanguel":
					$str = "한글날";
					break;		
				case "christmas":
					$str = "크리스마스";
					break;																		
				}
				return true;
			}
		}
	}
	return false;
}

$check;
$s_first;
$s_second;
function GetSchedule($date, $option = 0)
{
	global $db_passwd;
	global $check;
	global $s_first;
	global $s_second;
	if($mydb = mysql_connect("localhost", "root", $db_passwd))
	{
		if(mysql_select_db("planner", $mydb))
		{
			$datenum = str_replace("-", "", $date);
			if($check)
				$query = "select * from schedule where id = '".$_SESSION["id"]."' and replace(end, '-', '') >= '$datenum' and replace(start, '-', '') <= '$datenum' order by replace(start, '-', '') desc";
			else
				$query = "select * from schedule where id = '".$_SESSION["id"]."' and replace(end, '-', '') >= '$datenum' and replace(start, '-', '') <= '$datenum' order by (replace(end, '-', '') - replace(start, '-', '')) desc";
			$result = mysql_query($query);
			$check = false;
			if($option == 0)
			{
				?>
					스케줄<br>
					<ul>
				<?
			}
			if(mysql_num_rows($result) == 2)
			{
				if(!isset($s_first) && isset($s_second))
				{
					$result = mysql_query("select * from schedule where id = '".$_SESSION["id"]."' and replace(end, '-', '') >= '$datenum' and replace(start, '-', '') <= '$datenum' order by replace(start, '-', '') desc");
				}
				if(isset($s_first) && !isset($s_second))
				{
					$result = mysql_query("select * from schedule where id = '".$_SESSION["id"]."' and replace(end, '-', '') >= '$datenum' and replace(start, '-', '') <= '$datenum' order by replace(end, '-', '') asc");
				}
			}
			else if($option != 0 && (mysql_num_rows($result) >= 3 || (mysql_num_rows($result) == 2 && (!isset($s_first) && isset($s_second)))))
			{
				?>
					<div class="overflow"><?=mysql_num_rows($result)."+"?></div>
				<?
				return true;
			}
			for($i = 0; $i < mysql_num_rows($result); $i++)
			{
				$row = mysql_fetch_row($result);
				if(!$row[0])
				{
					break;
				}
				if($i==2 && $option != 0)
				{
					return true;
					break;
				}
				if($option == 0)
				{
					?>
						<li><a href="ViewSchedule.html?title=<?=$row[0]?>" target="_blank"><?=$row[0]?></a></li><br>
					<?	
				}
				else
				{
					if($s_second == $row[0] && mysql_num_rows($result) != 2)
					{
						?>
							<div class="blank"><br></div>
						<?
					}
					?>
						<div class="label_schedule <?if($date == $row[2])echo(" start"); if($date == $row[3])echo(" end");?>"><?=$row[0]?></div>
					<?
					
					if($date == $row[2])
					{
						if(!isset($s_first) && !$bool)
						{
							$s_first = $row[0];
						}
						else if(!isset($s_second))
						{
							$s_second = $row[0];
							$bool = false;
						}
					}
					if($date == $row[3])
					{
						$check = true;
						if($s_first == $row[0])
						{
							$s_first = NULL;
							$bool = true;								
						}
						if($s_second == $row[0])
							$s_second = NULL;
					}

				}
			}
			if($option == 0)
			{
				?>
					</ul>
				<?
			}
		}
	}
}

$check2;
$t_first;
$t_second;
function GetToDo($date, $option = 0)
{
	global $db_passwd;
	global $check2;
	global $t_first;
	global $t_second;
	if($mydb = mysql_connect("localhost", "root", $db_passwd))
	{
		if(mysql_select_db("planner", $mydb))
		{
			$datenum = str_replace("-", "", $date);
			if($check2)
				$query = "select * from todo where id = '".$_SESSION["id"]."' and replace(end, '-', '') >= '$datenum' and replace(start, '-', '') <= '$datenum' order by replace(start, '-', '') desc";
			else
				$query = "select * from todo where id = '".$_SESSION["id"]."' and replace(end, '-', '') >= '$datenum' and replace(start, '-', '') <= '$datenum' order by (replace(end, '-', '') - replace(start, '-', '')) desc";
			$result = mysql_query($query);
			$check2 = false;
			if($option == 0)
			{
				?>
					할일<br>
					<ul>
				<?
			}
			if(mysql_num_rows($result) == 2)
			{
				if(!isset($t_first) && isset($t_second))
				{
					$result = mysql_query("select * from todo where id = '".$_SESSION["id"]."' and replace(end, '-', '') >= '$datenum' and replace(start, '-', '') <= '$datenum' order by replace(start, '-', '') desc");
				}
				if(isset($t_first) && !isset($t_second))
				{
					$result = mysql_query("select * from todo where id = '".$_SESSION["id"]."' and replace(end, '-', '') >= '$datenum' and replace(start, '-', '') <= '$datenum' order by replace(end, '-', '') asc");
				}
			}
			else if($option != 0 && (mysql_num_rows($result) >= 3 || (mysql_num_rows($result) == 2 && (!isset($t_first) && isset($t_second)))))
			{
				?>
					<div class="overflow"><?=mysql_num_rows($result)."+"?></div>
				<?
				return true;
			}
			for($i = 0; $i < mysql_num_rows($result); $i++)
			{
				$row = mysql_fetch_row($result);
				if(!$row[0])
				{
					break;
				}
				if($i==2 && $option != 0)
				{
					return true;
					break;
				}
				if($option == 0)
				{
					?>
						<li><a href="ViewToDo.html?title=<?=$row[0]?>" target="_blank"><?=$row[0]?></a></li><br>
					<?	
				}
				else
				{
					if($t_second == $row[0] && mysql_num_rows($result) != 2)
					{
						?>
							<div class="blank"><br></div>
						<?
					}
					?>
						<div class="label_todo <?if($date == $row[2])echo(" start"); if($date == $row[3])echo(" end");?>"><?=$row[0]?></div>
					<?
					
					if($date == $row[2])
					{
						if(!isset($t_first) && !$bool)
						{
							$t_first = $row[0];
						}
						else if(!isset($s_second))
						{
							$t_second = $row[0];
							$bool = false;
						}
					}
					if($date == $row[3])
					{
						$check2 = true;
						if($t_first == $row[0])
						{
							$t_first = NULL;
							$bool = true;								
						}
						if($t_second == $row[0])
							$t_second = NULL;
					}

				}
			}
			if($option == 0)
			{
				?>
					</ul>
				<?
			}
		}
	}
}



function ViewSchedule($title)
{
	global $db_passwd;
	$mydb = mysql_connect("localhost", "root", $db_passwd);
	mysql_select_db("planner", $mydb);
	$query = "select * from schedule where id = '".$_SESSION["id"]."' and schedule = '$title'";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	?>
	<form action="schedule_edit.php" method="post">
		<input type="hidden" name="extitle" value="<?=$row[0]?>">
		<input type="hidden" name="exstart" value="<?=$row[2]?>">
		<table border="1">
			<tr>
				<td>제목</td>
				<td><input type="checkbox" id="schedule" class="tab" name="tab"><label for="schedule">
					<div class="info"><?=$row[0]?></div></label>
					<div class="edit"><input type="text" name="schedule" value="<?=$row[0]?>"</div></td>
			</tr>
			<tr>
				<td>중요도</td>
				<td><input type="checkbox" id="importance" class="tab" name="tab"><label for="importance">
					<div class="info"><?=$row[1]?></div></label>
					<div class="edit"><input type="text" name="importance" value="<?=$row[1]?>"</div></td>
			</tr>
			<tr>
				<td>시작일</td>
				<td><input type="checkbox" id="start" class="tab" name="tab"><label for="start">
					<div class="info"><?=$row[2]?></div></label>
					<div class="edit"><input type="text" name="start" value="<?=$row[2]?>"</div></td>
			</tr>
			<tr>
				<td>마감일</td>
				<td><input type="checkbox" id="end" class="tab" name="tab"><label for="end">
					<div class="info"><?=$row[3]?></div></label>
					<div class="edit"><input type="text" name="end" value="<?=$row[3]?>"</div></td>
			</tr>
			<tr>
				<td>장소</td>
				<td><input type="checkbox" id="place" class="tab" name="tab"><label for="place">
					<div class="info"><?=$row[4]?></div></label>
					<div class="edit"><input type="text" name="place" value="<?=$row[4]?>"</div></td>
			</tr>
			<tr>
				<td>설명</td>
				<td><input type="checkbox" id="explain" class="tab" name="tab"><label for="explain">
					<div class="info"><?=$row[5]?></div></label>
					<div class="edit"><input type="text" name="explain" value="<?=$row[5]?>"</div></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" value="확인">
					<a href="delete.php?num=<?=$row[7]?>&kind=1"><input type="button" value="삭제"></a>
				</td>
			</tr>
		</table>
		</form>
		<form method="post" action="share.php">
			<input type="hidden" name="num" value=<?=$row[7]?>>
			<input type="hidden" name="kind" value="schedule">
		<div>
			<?
				$groups = check_group();
				?>
					<select name="group">
				<?
				for($i = 0; $i < count($groups); $i++)
				{
					?>
						<option value="<?=$groups[$i]?>"><?=$groups[$i]?></option>
					<?
				}
			?>
					</select>
				<input type="submit" value="공유하기">
		</div>
		</form>
	<?
}

function ViewToDo($title)
{
	global $db_passwd;
	$mydb = mysql_connect("localhost", "root", $db_passwd);
	mysql_select_db("planner", $mydb);
	$query = "select * from todo where id = '".$_SESSION["id"]."' and title = '$title'";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	?>
	<form action="todo_edit.php" method="post">
		<input type="hidden" name="extitle" value="<?=$row[0]?>">
		<input type="hidden" name="exstart" value="<?=$row[2]?>">
		<table border="1">
			<tr>
				<td>제목</td>
				<td><input type="checkbox" id="title" class="tab" name="tab"><label for="title">
					<div class="info"><?=$row[0]?></div></label>
					<div class="edit"><input type="text" name="title" value="<?=$row[0]?>"</div></td>
			</tr>
			<tr>
				<td>중요도</td>
				<td><input type="checkbox" id="importance" class="tab" name="tab"><label for="importance">
					<div class="info"><?=$row[1]?></div></label>
					<div class="edit"><input type="text" name="importance" value="<?=$row[1]?>"</div></td>
			</tr>
			<tr>
				<td>시작일</td>
				<td><input type="checkbox" id="start" class="tab" name="tab"><label for="start">
					<div class="info"><?=$row[2]?></div></label>
					<div class="edit"><input type="text" name="start" value="<?=$row[2]?>"</div></td>
			</tr>
			<tr>
				<td>마감일</td>
				<td><input type="checkbox" id="end" class="tab" name="tab"><label for="end">
					<div class="info"><?=$row[3]?></div></label>
					<div class="edit"><input type="text" name="end" value="<?=$row[3]?>"</div></td>
			</tr>
			<tr>
				<td>설명</td>
				<td><input type="checkbox" id="explain" class="tab" name="tab"><label for="explain">
					<div class="info"><?=$row[4]?></div></label>
					<div class="edit"><input type="text" name="explain" value="<?=$row[4]?>"</div></td>
			</tr>
			<tr>
				<td>완료 여부</td>
				<td><div class="check"><input type="checkbox" name="check" value="1" 
					<?
						if($row[5] == 1)
							echo("checked");
					?>>
				</div></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" value="확인">
					<a href="delete.php?num=<?=$row[7]?>&kind=2"><input type="button" value="삭제"></a>
				</td>
			</tr>
		</table>
	</form>
	<form method="post" action="share.php">
			<input type="hidden" name="num" value=<?=$row[7]?>>
			<input type="hidden" name="kind" value="ToDo">
		<div>
			<?
				$groups = check_group();
				?>
					<select name="group">
				<?
				for($i = 0; $i < count($groups); $i++)
				{
					?>
						<option value="<?=$groups[$i]?>"><?=$groups[$i]?></option>
					<?
				}
			?>
					</select>
				<input type="submit" value="공유하기">
		</div>
		</form>
	<?
}

	function check_group()
	{
		global $db_passwd;
		$mydb = mysql_connect("localhost", "root", $db_passwd);
		mysql_select_db("planner", $mydb);
		$query = "select * from g where member like '%".$_SESSION["id"]."%'";
		$result = mysql_query($query);
		for($i = 0; $i < mysql_num_rows($result); $i++)
		{
			$row = mysql_fetch_array($result);
			$return[$i] = $row["name"];
		}
		return $return;
	}
	function group_ranking($group)
	{
		global $db_passwd;
		$mydb = mysql_connect("localhost", "root", $db_passwd);
		mysql_select_db("planner", $mydb);
		$query = "select * from g where name='$group'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$user = explode("/", $row["member"]);
		?>
			<div class="ranking">
				<table>
					<tr>
						<td>순위</td>
						<td>이름</td>
						<td>달성률</td>
					</tr>
					<?
						$query = "select * from member where ";
						for($i = 0; $i < count($user); $i++)
						{
							$query .= "id='$user[$i]' ";
							if($i != count($user) - 1)
							{
								$query .= "or ";
							}
						}
						$query .= " order by rate desc";
						$result = mysql_query($query);
						for($i = 0; $i < count($user); $i++)
						{
							$row = mysql_fetch_array($result);
							?>
								<tr>
									<td><?=$i+1?></td>
									<td><?=$row["id"]?></td>
									<td><?=$row["rate"]?></td>
								</tr>
							<?
						}
					?>
				</table>
			</div>
		<?
	}
function change_memo($friend,$memo,$id)
{
	global $db_passwd;
   $connect = mysql_connect('localhost','root',$db_passwd);
   if($connect)
   {	
   		$db=mysql_select_db('planner');
   		if($db)
   		{
   			$query = mysql_query("update friend set content='".$memo[$friend]."' where id='".$id."' and friend='".$friend."'");	
   		}
 
   }  
   ?>
   <meta http-equiv="refresh" content="0; URL=p_setfriend.html">
   <?
}
function invite_mem($friend,$id,$group)
{
	global $db_passwd;
   $connect = mysql_connect('localhost','root',$db_passwd);
   if($connect)
   {	
   		$db=mysql_select_db('planner');
   		$query=mysql_query("select * from member where id='".$friend."'");
   		var_dump(mysql_fetch_array($query));
   		if(mysql_num_rows($query)!=0)
   		{ 
   			$query2 = mysql_query("select * from g where name='".$group."' and member like '%".$friend."%'");
   			if($query2==0)
   			{
   				$_SESSION["alram"]="이미 그룹 맴버입니다.";
   				return 0;

   			}
   			else
   			{
   				$query3 = mysql_query("select invite from g where name='".$group."' and invite like '%".$friend."%'");
   				if(mysql_num_rows($query3)==0)
   				{
   					$invites = mysql_fetch_array($query3);
   					$new_invites=$invites['invite'].$friend;
   					$add=mysql_query("update g set invite='".$new_invites."' where name='".$group."'");
   					$_SESSION["alram"]="초대를 보냈습니다.";
   					return 1;
   				}
   				else
   				{
   					$_SESSION["alram"]="이미 초대를 보냈습니다.";
   					return 0;
   				}

   			}
   		}
   		else
   		{
   			$_SESSION['alram']="존재하지 않는 아이디입니다.";
   			return 0;
   		}

   	}
}

function insert_mem($id,$group)
{
	global $db_passwd;
	mysql_connect('localhost','root',$db_passwd);
	mysql_select_db('planner');
	$query=mysql_query("select invite from g where name='".$group."'");
	$query_fetch = mysql_fetch_array($query);
	$group_mem=explode('/', $query_fetch[0]);
	//invite에서 내 아이디 삭제.
	for($i=0; $i<count($group_mem);$i++)
	{
		if($group_mem[0]!=$id)
		{
			$invite_mem=$invite_mem.$group_mem[0];
		}
	}
	$query2=mysql_query("update g set invite='".$invite_mem."' where name='".$group."'");

	//member에 내 아이디 추가.
	$query3 =mysql_query("select member from g where name='".$group."'");
	$pre_member=mysql_fetch_row($query3);
	$new_members=$pre_member[0]."/".$id;
	var_dump($new_members);
	$query4=mysql_query("update g set member='".$new_members."' where name='".$group."'");

}

function add_FriendBirth($user1, $user2)
{
   global $db_passwd;
   $db = mysql_connect("localhost","root",$db_passwd);
      mysql_select_db("planner",$db);
      $cur_year = date("Y");

      $query = "select * from member where id='$user1'";
      $result = mysql_query($query);
      $user1_row = mysql_fetch_array($result);
      $user1_month = $user1_row["birthmonth"];
      $user1_day = $user1_row["birthday"];

      $query = "select * from member where id='$user2'";
      $result = mysql_query($query);
      $user2_row = mysql_fetch_array($result);
      $user2_month = $user2_row["birthmonth"];
      $user2_day = $user2_row["birthday"];

      for($i = $cur_year; $i <= 2100; $i++)
      {
         $date = date_format(date_create($i."-".$user1_month."-".$user1_day), "Y-m-d");
         $query = "insert into schedule (schedule, importance, start, end, id) values('".$user1."_birth', 1, '$date', '$date', '$user2')";
         mysql_query($query);

         $date = date_format(date_create($i."-".$user2_month."-".$user2_day), "Y-m-d");
         $query = "insert into schedule (schedule, importance, start, end, id) values('".$user2."_birth', 1, '$date', '$date', '$user1')";
         mysql_query($query);
      }
}

function add_Group($name)
{
	$mydb = mysql_connect("localhost", "root", "apmsetup");
	mysql_select_db("planner", $mydb);
	if($name != NULL)
	{
		$query = "insert into g (name, member, master) values('$name', '".$_SESSION["id"]."', '".$_SESSION["id"]."')";
		mysql_query($query);
	}
}

?>