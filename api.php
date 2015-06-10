<?php
include 'db.php';
if(isset($_GET['install']) && $_GET['install']='1'){
    
    $sql="drop table if exists stats";
    $retval = mysql_query( $sql, $conn );
    if(! $retval ) die('Could not drop table: ' . mysql_error());
    print 'Drop table success<br>';
    $sql = "CREATE TABLE IF NOT EXISTS `stats` (
          `id` bigint(20) unsigned NOT NULL primary key AUTO_INCREMENT,
          `data` text NOT NULL,
          `creation` datetime NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    $retval = mysql_query( $sql, $conn );
    if(! $retval ) die('Could not create table: ' . mysql_error());
    print 'Create table success<br>';
   
    print 'Installation complete<br>';
    
}
if( isset($_GET['view']) && intval($_GET['view'])>0 ){
     $sql = sprintf("select * from stats order by creation desc limit ".intval($_GET['view'])."");
     if($q=mysql_query( $sql, $conn )){
        print json_encode(array('code'=>1,'msg'=>'OK','stat'=>$stat));
        $data=array();
        while($r=mysql_fetch_array($q)){
            $data[$r['creation']]=json_decode($r['data'],1);
        }
        print json_encode(array('code'=>0,'msg'=>'OK','data'=>$data));
     }
     else{
        print json_encode(array('code'=>1,'msg'=>'ERROR','error'=>mysql_error(),'debug'=>$sql));
     }
}
else{
    if( isset($_POST['stat']) && $_POST['stat']!='' ){
        $stat=mysql_real_escape_string($_POST['stat']);
        $sql = sprintf('INSERT INTO `stats`(`data`, `creation`) VALUES (\'%s\',now())',$stat);
        if(mysql_query( $sql, $conn )){
            print json_encode(array('code'=>1,'msg'=>'OK','stat'=>$stat));
        }
        else{
            print json_encode(array('code'=>0,'msg'=>'ERROR','error'=>mysql_error(),'debug'=>array('sql'=>$sql),'stat'=>NULL));
        }
    }
    else{
        print json_encode(array('code'=>2,'msg'=>'ERROR','error'=>'No data','debug'=>array('POST'=>$_POST),'stat'=>NULL));
    }
}
mysql_close($conn); 