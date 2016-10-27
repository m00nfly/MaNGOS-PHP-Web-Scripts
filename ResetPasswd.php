<?php
/*
需要给 realmd.account 添加一个 reset_password 字段，SQL如下：
ALTER TABLE `account` ADD `reset_password` VARCHAR( 50 ) NOT NULL;

PHP 需要 mail() 函数支持；请在 php.ini中定义好smtp相关参数，邮件才能发送成功；
*/

/*Config*/
$realmd = array(
'db_host'=> 'localhost', //ip of db realm
'db_username' => 'mangos',//realm user
'db_password' => '',//realm password
'db_name'=> 'realmd',//realm db name
);

$config = array(
'path_to_thisfile' => 'http://mysite.com/wow/ResetPasswd.php', //访问本脚本的完整URL路径，用于拼接密码找回邮件中的超链接；
'email_from' => 'wowmaster@mysite.com', // 定义密码重置通知邮件的发件人地址
'email_subject' => 'Password recovery for our site!', // 定义密码重置邮件的主题
);



function sha_password($user,$pass){
$user = strtoupper($user);
$pass = strtoupper($pass);

return SHA1($user.':'.$pass);
}
function random_string($counts){
$str = "abcdefghijklmnopqrstuvwxyz";//Count 0-25
for($i=0;$i<$counts;$i++){
if ($o == 1){
$output .= rand(0,9);
$o = 0;
}else{
$o++;
$output .= $str[rand(0,25)];
}

}
return $output;
}


$realmd_bc_new_connect = mysql_connect($realmd[db_host],$realmd[db_username],$realmd[db_password]);
$selectdb = mysql_select_db($realmd[db_name],$realmd_bc_new_connect);

if ($_GET[h] && $_GET[h] != '' && $_GET[h] != '0'){
$output_random_pass = random_string(10);
$query = mysql_query("SELECT username FROM `account` WHERE reset_password='$_GET[h]'");
$res = mysql_fetch_array($query);
if (mysql_num_rows($query) == 1){
echo "你好 $res[username]，您的密码当前已经被系统重新设置为: $output_random_pass. 请尽快登录修改这个密码！";
$pass_hash = sha_password($res[username],$output_random_pass);
mysql_query("UPDATE `account` SET sha_pass_hash='$pass_hash' WHERE reset_password='$_GET[h]'");
mysql_query("UPDATE `account` SET reset_password='' WHERE username='$res[username]'");
}else{
echo "Error.";
}

}else{
?>

<?php
/*
用户判断及邮件发送处理
TODO: 邮件主题及内容增加 UTF8 中文内容的支持
      重设密码的处理逻辑，增加防注入过滤
*/
if ($_POST[password_takeback]){
$check_security = mysql_query("SELECT id FROM `account` WHERE username='$_POST[username]' AND email='$_POST[email]'");
if (isset($_POST['username']) && isset($_POST['email']) && mysql_num_rows($check_security) == 1){
 $rand = random_string(40);
mysql_query("UPDATE `account` SET reset_password='$rand' WHERE username='$_POST[username]'");
$to = $_POST["email"];
$from = "From: $config[email_from]";
$subject = $config[email_subject];
$message= "Hi $_POST[username], you have submitted a password recovery on our site. IF YOU DIDNT SUBMIT A PASSWORD REQUEST JUST DELETE THIS MAIL!. Please follow this link to complete the operation: $config[path_to_thisfile]?h=$rand";
mail($to, $subject, $message, $from);
echo "密码重置邮件已经发送到您所填写的邮箱，请根据邮件中的提示完成密码找回的后续操作！";
}else{
echo "操作失败！！ 请再次确认您所填写的邮箱地址和登录账号是否正确！";
}
}else{
?>
<form action="<?php echo $_SERVER[PHP_SELF]; ?>" method="POST">
您的邮箱地址: <input type="text" name="email">

您的邮箱账号: <input type="text" name="username">

<input type="submit" name="找回密码">
</form>
<?php
}
}// End GET
?>
