<?php
$db_name='homestats_db';
$db_user='***';
$db_pass='***';
$db_host='***';
$db_port=3306;
$conn = mysql_connect($db_host, $db_user, $db_pass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_name);
$limit=isset($_GET['view']) && intval($_GET['view'])>0?intval($_GET['view']):1000;
$step=isset($_GET['step']) && intval($_GET['step'])>0?intval($_GET['step']):1;
$sql = sprintf("select * from stats order by creation desc limit ".$limit."");
$data=array();
if($q=mysql_query( $sql, $conn )){	
	$i=0;
	while($r=mysql_fetch_array($q)){		
		$x=json_decode($r['data'],1);
		$i++;
		if($i==$step){
			$data[]=array('time'=>date('d/m/Y H:i:s',strtotime($r['creation'])),'temp'=>floatval($x['Temprerature']),'humid'=>floatval($x['Humidity']));
			$i=0;
		}
	}
}
else{
	print mysql_error();
}
$data=array_reverse($data);
print  json_encode($data);
/*
$temp='';
$humid='';

foreach($data as $d){
	$date=date('d/m/Y H:i:s',strtotime($d['time']));
	$temp.='[\''.$date.'\','.$d['temp'].'],';
	$humid.='[\''.$date.'\','.$d['humid'].'],';
}
*/
?>
