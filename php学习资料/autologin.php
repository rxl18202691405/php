<?php
header("content-type:text/html;charset=utf-8");
mysql_connect('localhost','root','');
mysql_select_db('test1');
mysql_query('SET NAMES gb2312');

$m = md5("zxcvbnm");
if($_POST){
	$rember=$_POST["rember"];
	$username=trim($_POST["username"]);
	$password=md5($_POST["password"]);

	$sql="select * from user where username='".$username."' and password = '".$password."'";
 
	$stmt=mysql_query($sql);
	while($res =mysql_fetch_assoc($stmt)){
		$rows = $res;
	}
	if(!empty($rows)){
		//验证成功，存cookie
		if($rember == "2"){
			setcookie($m,md5($_POST["username"]),time()+3600*24*7);
		}
		if($rember == "3"){
			setcookie("username",$_POST["username"],time()+3600*24*31);
		}
		if($rember == "4"){
			setcookie("username",$_POST["username"],time()+3600*24*365);
		}
		//跳主页
		 header("location:1.html");
	}else{

	  echo "没有这个用户";
	}
}else{
	//判断用户选择记住密码状态
	if(!empty($_COOKIE[$m])){  
		header("location:1.html");
	}
}
?>

<form action="remember.php" method="post">
 用户名:<input type="text" name="username" id="username"/><br>
 密码:<input type="password" name="password"/><br>
	记住密码：从不<input type="radio" name="rember" value="1" checked>
	一周<input type="radio" name="rember" value="2">
	一月<input type="radio" name="rember" value="3">
	一年<input type="radio" name="rember" value="4"><br>
 <input type="submit" name="submit" value="登录" >
 </form>
