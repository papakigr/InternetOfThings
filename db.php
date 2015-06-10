<?php
$db_name='<your db name>';
$db_user='<your db user>';
$db_pass='<your db password>';
$db_host=',your db host>';
$db_port=3306;
$conn = mysql_connect($db_host, $db_user, $db_pass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_name);