<?php
require_once ( 'config.php');

mysql_connect($host, $user, $pass) or die ("Can't connect with $host");
mysql_selectdb ("$mangosrealm");

$sql = mysql_query ("SELECT * FROM uptime ORDER BY `starttime` DESC LIMIT 1");  
$uptime_results = mysql_fetch_array($sql);    

if ($uptime_results['uptime'] > 86400) { 
    $uptime =  round(($uptime_results['uptime'] / 24 / 60 / 60),2)." Days";
}
elseif($uptime_results['uptime'] > 3600) { 
    $uptime =  round(($uptime_results['uptime'] / 60 / 60),2)." Hours";
}
else { 
    $uptime =  round(($uptime_results['uptime'] / 60),2)." Min";
}

echo "Uptime:$uptime <br>";
?>
