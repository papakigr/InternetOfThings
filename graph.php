<?php
include 'db.php';
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
			$data[]=array('time'=>$r['creation'],'temp'=>$x['Temprerature'],'humid'=>$x['Humidity']);
			$i=0;
		}
	}
}
else{
	print mysql_error();
}
$data=array_reverse($data);
$temp='';
$humid='';

foreach($data as $d){
	$date=date('d/m/Y H:i:s',strtotime($d['time']));
	$temp.='[\''.$date.'\','.$d['temp'].'],';
	$humid.='[\''.$date.'\','.$d['humid'].'],';
}
?>
<!DOCTYPE html>
<html lang="el">
  <head>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<script src="moment.min.js"></script>
  <script type="text/javascript">
   /* SetTimeOD();
	function SetTimeOD(){
		var styles = ['night', 'dawn', 'afternoon', 'day', 'evening'];
        var currStyle = styles[Math.floor(styles.length * (new Date()).getHours() / 24)];
		$('body').attr('class', currStyle);
		setTimeout(SetTimeOD,5000);
	}*/
	function Widget(data) {
		$('#widget .loading').show();
		$("#widget .the-data").empty();
		  if(data.temp > 20) {
			$("#widget .the-data").css({backgroundColor: '#F7AC57'}, 1500);
		  } else {
			$("#widget .the-data").css({backgroundColor: '#0091c2'}, 1500);
		  }
		  html = '<div class="data-'+data.code+'"><span class="iconPM"></span><h1 class="icon-'+data.code+'"></h1>';
		  html += '<h2>T: '+data.temp+'&deg;</h2>';
		  html += '<h2>H: '+data.humid+'&deg;</h2>';
		  html += '</div>';
		  var timestamp = moment(data.updated);
		  html += '<p class="updated">Updated <span style="font-weight:bold;">'+moment(timestamp).format('MMMM Do YYYY, h:mm:ss a');+'</span></p>';
			
		  $("#widget .the-data").html(html);
		  $('#widget .loading').hide();
		  
		  setTimeout(function(){
			  $("#widget .the-data p.updated span").css({"font-weight": "normal"});
		  },1000);
		}
	function drawChart(dataRows) {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Datetime');
      data.addColumn('number', 'Temp');
		for(var i in dataRows){
			data.addRows([
				[dataRows[i].time,dataRows[i].temp]
			  ]);
		}
      
		
	var data2 = new google.visualization.DataTable();
      data2.addColumn('string', 'Datetime');
      data2.addColumn('number', 'Humid');

      /*data2.addRows([
		<?php print $humid;?>        
      ]);*/
	  for(var i in dataRows){
			data2.addRows([
				[dataRows[i].time,dataRows[i].humid]
			  ]);
		}
      var options = {
       
        hAxis: {
          title: 'Time'
        },
        vAxis: {
          title: 'Temperature'
        }
      };

      var chart = new google.visualization.LineChart(
      document.getElementById('ex0'));
      chart.draw(data, options);
		var options2 = {
        
        hAxis: {
          title: 'Time'
        },
        vAxis: {
          title: 'Humidity'
        }
      };
	var chart2 = new google.visualization.LineChart(
      document.getElementById('ex1'));
      chart2.draw(data2, options2);
    }
	function GetData(){
		var dataRows=[];
		$.getJSON( "/ajax.php", function( data ) {
		  drawChart(data);
		  var last=data[data.length-1];
		  var w={temp:last.temp,code:last.temp,humid:last.humid,updated:new Date()};
		  Widget(w);
		  setTimeout(GetData,5000);
		});
	}
	function blink(){
		$('.iconPM').delay(100).css({backgroundColor: 'white'}).animate({backgroundColor: 'green'},1000, blink);
	}
	$(document).ready(function() {
	 GetData();
	});

</script>
<style type="text/css">

@font-face {
    font-family: 'weather';
    src: url('/weather_icons/artill_clean_icons.otf');
    font-weight: normal;
    font-style: normal;
}

body {
  padding: 25px 0;
  font: 13px 'Open Sans', "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  background: #fff;
}

.loading {
  margin: 65px 0 0;
}

#widget {
  width: 400px;
  margin: 0px auto;
  text-align: center;
  text-transform: uppercase;font-size: 100px; 
}

#widget h1 {
  margin: -60px 0 30px;
  color: #fff;
  font-family: weather;
  font-size: 190%;
  font-weight: normal;
  line-height: 1.0;
}

#widget h1.icon-0:before { content: ":"; }
#widget h1.icon-1:before { content: "p"; }
#widget h1.icon-2:before { content: "S"; }
#widget h1.icon-3:before { content: "Q"; }
#widget h1.icon-4:before { content: "S"; }
#widget h1.icon-5:before { content: "W"; }
#widget h1.icon-6:before { content: "W"; }
#widget h1.icon-7:before { content: "W"; }
#widget h1.icon-8:before { content: "W"; }
#widget h1.icon-9:before { content: "I"; }
#widget h1.icon-10:before { content: "W"; }
#widget h1.icon-11:before { content: "I"; }
#widget h1.icon-12:before { content: "I"; }
#widget h1.icon-13:before { content: "I"; }
#widget h1.icon-14:before { content: "I"; }
#widget h1.icon-15:before { content: "W"; }
#widget h1.icon-16:before { content: "I"; }
#widget h1.icon-17:before { content: "W"; }
#widget h1.icon-18:before { content: "U"; }
#widget h1.icon-19:before { content: "Z"; }
#widget h1.icon-20:before { content: "Z"; }
#widget h1.icon-21:before { content: "Z"; }
#widget h1.icon-22:before { content: "Z"; }
#widget h1.icon-23:before { content: "Z"; }
#widget h1.icon-24:before { content: "E"; }
#widget h1.icon-25:before { content: "E"; }
#widget h1.icon-26:before { content: "3"; }
#widget h1.icon-27:before { content: "a"; }
#widget h1.icon-28:before { content: "A"; }
#widget h1.icon-29:before { content: "a"; }
#widget h1.icon-30:before { content: "A"; }
#widget h1.icon-31:before { content: "6"; }
#widget h1.icon-32:before { content: "1"; }
#widget h1.icon-33:before { content: "6"; }
#widget h1.icon-34:before { content: "1"; }
#widget h1.icon-35:before { content: "W"; }
#widget h1.icon-36:before { content: "1"; }
#widget h1.icon-37:before { content: "S"; }
#widget h1.icon-38:before { content: "S"; }
#widget h1.icon-39:before { content: "S"; }
#widget h1.icon-40:before { content: "M"; }
#widget h1.icon-41:before { content: "W"; }
#widget h1.icon-42:before { content: "I"; }
#widget h1.icon-43:before { content: "W"; }
#widget h1.icon-44:before { content: "a"; }
#widget h1.icon-45:before { content: "S"; }
#widget h1.icon-46:before { content: "U"; }
#widget h1.icon-47:before { content: "S"; }

#widget h2 {
  margin: 0px 0 8px;
  color: #fff;
  font-size: 90%;
  font-weight: 300;
  text-align: center;
  text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.15);
}

#widget ul {
  margin: 0;
  padding: 0;
}

#widget li {
  background: #fff;
  background: rgba(255,255,255,0.90);
  margin: 0 15px;
  padding: 20px;
  display: inline-block;
  border-radius: 5px;
}

#widget .updated {
  opacity: 0.45;font-size:15%;
}
#widget .iconPM {
  width: 30px; background:url(/weather_icons/green_bl.gif);
  height: 30px;
  display: block;
  
}
</style>
  </head>
  <body>
    <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
    
          <div id="ex0"></div>
		  <div id="ex1"></div>
		  <div id="widget"><img src="loader.gif" alt="Loading..." class="loading"><div class="the-data"></div></div>
	<script type="text/javascript">
	    google.load('visualization', '1', {packages: ['corechart']});
		//google.setOnLoadCallback(drawChart);

    
      
	</script>
  </body>
 </html>