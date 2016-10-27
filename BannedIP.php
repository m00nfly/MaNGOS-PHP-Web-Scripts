<?php   
require_once ( 'config.php');

echo "<center><table border=1><tr><th>IP</th><th>原因</th><th>封锁时间</th><th>解锁时间</th></tr>"; 
  
$conn = mysql_connect($host, $user, $pass) or die ("Can't connect with $host");   
mysql_select_db($mangosrealm, $conn);   

$sql = "SELECT `ip`,`banreason`, `bandate`, `unbandate` FROM `ip_banned`";   
$result = mysql_query($sql, $conn);   

while ($result_data = mysql_fetch_array($result))   
{   
echo "<tr><td align=\"center\">".$result_data["ip"]."</td>";   
echo "<td align=\"center\">".$result_data["banreason"]."</td>";   
echo "<td align=\"center\">".date("d.m.Y H:m",$result_data["bandate"])."</td>";
echo "<td align=\"center\">".date("d.m.Y H:m",$result_data["unbandate"])."</td>";   
     
echo "</tr>";   

}   

mysql_close($conn);   

echo "</table></center>";   
?>
