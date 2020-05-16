 <?php
 /*Get请求远程内容函数
 url 请求网址
 head 模似请求头
 foll 是否自动跳转
 ref 来路
 head 是否设置头
 */
 $ip=$_SERVER['REMOTE_ADDR'];
    $head=array(
 'X-FORWARDED-FOR:'.$ip,
 'CLIENT-IP:'.$ip,
 'Accept-Language: zh-cn',
 'Accept-Encoding:gzip,deflate',
 'Connection: Keep-Alive',
 'Accept: text/plain, */*',
 'X-Requested-With:XMLHttpRequest',
 'Cache-Control: no-cache',
 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)'
 );
 
 function get($url,$head=false,$foll=1,$ref=false){
    $curl = curl_init(); // 启动一个CURL会话
    if($head){
    curl_setopt($curl,CURLOPT_HTTPHEADER,$head);//模似请求头
    }
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址           
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION,$foll); // 使用自动跳转
    if($ref){
    curl_setopt($curl, CURLOPT_REFERER, $ref);//带来的Referer
    }else{
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    }
    curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']); // 存放Cookie信息的文件名称
    curl_setopt($curl, CURLOPT_COOKIEFILE,$GLOBALS ['cookie_file']); // 读取上面所储存的Cookie信息
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');//解释gzip
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {echo 'Errno'.curl_error($curl);}
    $data[]=curl_getinfo($curl);
    curl_close($curl); // 关键CURL会话
    $data[]=$tmpInfo;
    return $data; // 返回数据
}

##########################################################################
#
# 登陆块 QQ号 密码  验证马
#
##########################################################################

function qqlogin($user,$newpwd,$check){
global $head;
$d1=get('http://ui.ptlogin2.qq.com/cgi-bin/login?appid=1006102&s_url=http://ui.ptlogin2.qq.com/cgi-bin/login?appid=1006102&daid=1&style=13&s_url=http://id.qq.com/index.html',$head,0,'');
/*登陆提交*/
$login_sig = preg_message($d1[1],'login_sig:"[subject]"', "subject",-1);
$d2=get('http://ptlogin2.qq.com/login?u='.$user.'&p='.$newpwd.'&verifycode='.$check.'&aid=1006102&u1=http%3A%2F%2Fid.qq.com%2Findex.html&h=1&ptredirect=1&ptlang=2052&daid=1&from_ui=1&dumy=&low_login_enable=0&regmaster=&fp=loginerroralert&action=2-8-1388480527657&mibao_css=&t=1&g=1&js_ver=10062&js_type=1&login_sig='.$login_sig[0].'&pt_rsa=0',$head,0,$d1[0]['url']);
$n=iconv('utf-8','gbk//IGNORE',$d2[1]);
preg_match('/[0-9]/', $d2[1], $dx);
if($dx[0]=='4'){exit("验证码错误！");}
if($dx[0]=='3'){exit("密码错误");}
if($dx[0]=='7'){exit("很遗憾，网络连接出现异常，请您稍后再试");}
if($dx[0]=='0'){
$tourl = preg_message($d2[1],"http[subject]'", "subject",-1);
$d2=get('http'.$tourl[0],$head,0,$d2[0]['url']);
return $d2[1];
}
return false;
}
function qqloginwb($user,$newpwd,$check){
global $head;
$d1=get('http://ui.ptlogin2.qq.com/cgi-bin/login?appid=46000101&style=13&lang=&low_login=1&hide_title_bar=1&hide_close_icon=1&self_regurl=http%3A//reg.t.qq.com/index.php&s_url=http%3A%2F%2Ft.qq.com&daid=6',$head,0,'');
/*登陆提交*/
$login_sig = preg_message($d1[1],'login_sig:"[subject]"', "subject",-1);
$d2=get('http://ptlogin2.qq.com/login?u='.$user.'&p='.$newpwd.'&verifycode='.$check.'&aid=46000101&u1=http%3A%2F%2Ft.qq.com&h=1&ptredirect=1&ptlang=2052&daid=6&from_ui=1&dumy=&low_login_enable=1&low_login_hour=720&regmaster=&fp=loginerroralert&action=5-20-1388688558184&mibao_css=&t=1&g=1&js_ver=10062&js_type=1&login_sig='.$login_sig[0].'&pt_rsa=0',$head,0,$d1[0]['url']);
$n=iconv('utf-8','gbk//IGNORE',$d2[1]);
preg_match('/[0-9]/', $d2[1], $dx);
if($dx[0]=='4'){exit("验证码错误！");}
if($dx[0]=='3'){exit("密码错误");}
if($dx[0]=='7'){exit("很遗憾，网络连接出现异常，请您稍后再试");}
if($dx[0]=='0'){
$tourl = preg_message($d2[1],"http[subject]'", "subject",-1);
$d2=get('http'.$tourl[0],$head,0,$d2[0]['url']);
return $d2[1];
}
return false;
}

 

##########################################################################
#
# 加密扣扣密码 分别是 验证码 + 密码 md5
#
##########################################################################
function password($userpasswrd,$check) {
return strtoupper(md5($userpasswrd.strtoupper($check)));
}
function md5_3($str) {
return strtoupper(md5(md5(md5($str,true),true)));
}

function check($user)
{
global $head;
 $url= "http://check.ptlogin2.qq.com/check?uin=".$user."&appid=1006102&r=0.8979688249785638";
;
 $check = get($url,$head,0,'');
 preg_match_all("/'(.*?)'/", $check[1], $verification);
    $dgital = $verification[1][0];
 $check = $verification[1][1];
 if($dgital==1) {
 if(isset($_GET['image'])){image("http://captcha.qq.com/getimage?aid=1&uin=".$user);}
getimage("http://captcha.qq.com/getimage?aid=1&uin=".$user);}

 return $check;
}
##########################################################################
#
# 检测是否显示图片  如果有get image  就显示图片并且覆盖第一个 cookie
#
##########################################################################
function image($url)
{

global $head;
 if(isset($_GET['image'])){
  header('Content-Type: image/jpeg');
  $im=get($url,$head,0,'');
  echo $im[1];
  die();
 }
}

function getimage($check)
{
 if(substr($check, 0, 4)=='http'){
  echo '<img src="?image&uin='.rand(100,999).'" alt="'.c1.'" name="verifyimg" id="verifyimg" style="cursor: pointer;" onclick="reloadcode(\'verifyimg\',\'?image&uin='.rand(100,999).'\')" />
  <script>
  function reloadcode(eid, src_str){
   var reload_e = document.getElementById(eid);
   reload_e.src = src_str + \'&\' + Math.random();
  }
  </script>
  <form  method="post">
  '.c2.'
  <input type="text" name="check">
  <input type="submit" value="'.c3.'">
  </form>';
  die();
 }else{
  return $check;
 }
}
 function post($url,$head=false,$foll=1,$ref=false,$post){
    $curl = curl_init(); // 启动一个CURL会话
    if($head){
    curl_setopt($curl,CURLOPT_HTTPHEADER,$head);//模似请求头
    }
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址           
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION,$foll); // 使用自动跳转
    if($ref){
    curl_setopt($curl, CURLOPT_REFERER, $ref);//带来的Referer
    }else{
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    }
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
    curl_setopt($curl, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']); // 存放Cookie信息的文件名称
    curl_setopt($curl, CURLOPT_COOKIEFILE,$GLOBALS ['cookie_file']); // 读取上面所储存的Cookie信息
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');//解释gzip
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {echo 'Errno'.curl_error($curl);}
    $data[]=curl_getinfo($curl);
    curl_close($curl); // 关键CURL会话
    $tmpInfo=preg_replace('/script/','js',$tmpInfo);
    $data[]=$tmpInfo;
    return $data; // 返回数据
}

 

###GTK计算类
 function getGTK($str){
   //$str = $cookie['skey'];
   $hash = 5381;
   $len = strlen($str);
   for($i = 0;$i < $len;$i++)
   {
 $h = ($hash << 5) + utf8_unicode($str[$i]);
      $hash+=$h;
   }
   return $hash & 0x7fffffff;
}
function utf8_unicode($c) {
switch(strlen($c)) {
    case 1:
      return ord($c);
    case 2:
      $n = (ord($c[0]) & 0x3f) << 6;
      $n += ord($c[1]) & 0x3f;
      return $n;
    case 3:
      $n = (ord($c[0]) & 0x1f) << 12;
      $n += (ord($c[1]) & 0x3f) << 6;
      $n += ord($c[2]) & 0x3f;
      return $n;
    case 4:
      $n = (ord($c[0]) & 0x0f) << 18;
      $n += (ord($c[1]) & 0x3f) << 12;
      $n += (ord($c[2]) & 0x3f) << 6;
      $n += ord($c[3]) & 0x3f;
      return $n;
}
}

 //截取字符
function preg_message($message, $rule, $getstr, $limit=1) {
 $result = array('0'=>'');
 $rule = conver_trule($rule);  //转义正则表达式特殊字符串
 $rule = str_replace('\['.$getstr.'\]', '\s*(.+?)\s*', $rule); //解析为正则表达式
 if($limit == 1) {
  preg_match("/$rule/is", $message, $rarr);
  if(!empty($rarr[1])) {
   $result[0] = $rarr[1];
  }
 } else {
  preg_match_all("/$rule/is", $message, $rarr);
  if(!empty($rarr[1])) {
   $result = $rarr[1];
  }
 }
 return $result;
}
/**
 * 转义正则表达式字符串
 */
function conver_trule($rule) {
 $rule = preg_quote($rule, "/");  //转义正则表达式
 $rule = str_replace('\*', '.*?', $rule);
 $rule = str_replace('\|', '|', $rule);
 return $rule;
}
/*转编码*/
function escape($str) {
  preg_match_all("/[-].|[\x01-]+/",$str,$r);
  $ar = $r[0];
  foreach($ar as $k=>$v) {
    if(ord($v[0]) < 128)
      $ar[$k] = rawurlencode($v);
    else
      $ar[$k] = "%u".bin2hex(iconv("GB2312","UCS-2",$v));
  }
  return join("",$ar);
}

 

 

 

 

 

 


