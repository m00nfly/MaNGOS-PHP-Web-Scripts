<?php 
require_once ( 'config.php');
if (! $sock = @fsockopen($ip, $port, $num, $error, 3)) 
echo '<FONT COLOR=red>Off</FONT>';
else{ 
echo '<FONT COLOR=yellow>On</FONT>'; 
fclose($sock);
} 
?>
