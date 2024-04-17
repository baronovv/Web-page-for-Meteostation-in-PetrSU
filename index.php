<?php
	$csv=file_get_contents("http://asterion.petrsu.ru/meteo/cache/meteo.csv");

	
	$data=[];
	foreach(str_getcsv($csv,"\n") as $v){
		$v=str_getcsv($v,";");

		
		if($v[4]>900||$v[4]<-900){
			if(isset(array_slice($data,-1)[0])){
				$v[4]=array_slice($data,-1)[0][4];
			}else{
				$v[4]=0;
			}
		}

		$v[0]=explode(" ", $v[0]);


		$data[]=$v;
	}


	$temperature_label=[];
	$temperature_air=[];
	$temperature_dew=[];
	$temperature_ir=[];
	$humidity=[];
	$illumination=[];
	$wind_speed=[];

	// $i = 0;
	foreach($data as $v) {
		// $i=($i+1)%5;
		// if($i!=0)continue;

		$temperature_label[]=mb_substr($v[0][1],0,-3);
		$temperature_air[]=$v[5];
		$temperature_dew[]=$v[10];
		$temperature_ir[]=$v[4];
		$humidity[]=$v[9];
		$illumination[]=$v[13];
		$wind_speed[]=$v[6];
	}


	# Получаем текущие данные
	$t=array_slice($data,-1)[0];

	$icon_day="sun";
	$icon_weather="";
	if($t[13]<300)$icon_day="moon";

	if($t[3]=='3'){
		$icon_weather="rain";
	}else if($t[3]=='2'){
		$icon_weather="drop";
	}else if($t[1]=='2'){
		$icon_weather="cloud";
	}else if($t[1]=='3'){
		$icon_weather="clouds";
	}
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Метеостанция лаборатории астрономии ПетрГУ</title>
</head>
<nav>
	<div class="content">
		<a id="logo" href="http://asterion.petrsu.ru/">
			<h1>Астерион</h1>
			<span>астрономический клуб</span>
		</a>
		<span id="subtitle">meteo station</span>
		<span id="city">Petrozavodsk</span>
	</div>
</nav>
<section class="content">
	<h2>Сегодня <span><?=$t[0][0]?></span> <span><?=$t[0][1]?></span></h2>
	<div id="info">
		<div class="info">
			<div class="i">Температура <b><?=$t[5]?>°c</b></div>
			<div class="i">Точка росы <b><?=$t[10]?>°c</b></div>
			<div class="i">IR-Температура неба <b><?=$t[4]?>°c</b></div>
		</div>
		<div class="info">
			<div class="i">Ветер <b><?=round($t[6],1)?> m/s</b></div>
			<div class="i">Влажность <b><?=$t[9]?>%</b></div>
			<div class="i">Освещенность <b><?=$t[13]?> a.u.</b></div>
		</div>
		<div class="info">
			<img id="icon_day" src="/img/<?=$icon_day?>.png">
			<?php if(!empty($icon_weather)){?>
				<img id="icon_weather" src="/img/<?=$icon_weather?>.png">
			<?php }?>
		</div>
	</div>
	<div class="chart">
		<span>Температура воздуха °c</span>
		<canvas id="temperature"></canvas>
	</div>
	<div class="chart mini">
		<span>IR-температура неба °c</span>
		<canvas id="temperature_ir"></canvas>
	</div>
	<div class="chart mini">
		<span>Влажность %</span>
		<canvas id="humidity"></canvas>
	</div>
	<div class="chart mini">
		<span>Освещенность</span>
		<canvas id="illumination"></canvas>
	</div>
	<div class="chart mini">
		<span>Скорость ветра m/s</span>
		<canvas id="wind_speed"></canvas>
	</div>

	<div id="footer">
		<div id="f_left">
			Данные получены с метеостанции <a href="http://diffractionlimited.com/product/boltwood-cloud-sensor-ii/" target="_blank">Boltwood Cloud Sensor II</a>,<br> установленной на крыше 6 учебного корпуса<br> Петрозаводского Гос. Университета. <br>Координаты: <a href="https://www.google.ru/maps/place/61%C2%B046'21.7%22N+34%C2%B016'55.7%22E/@61.7727028,34.2821472,442m/data=!3m1!1e3!4m2!3m1!1s0x0:0x0" target="_blank">61°46'21.73"N 34°16'55.73"E</a> <br>Замеры и информация на сайте обновляются каждые 10 минут.<br> Архив данных доступен для просмотра по <a href="http://asterion.petrsu.ru/meteo/cache/meteo.csv" target="_blank">ссылке</a>
		</div>
		<div id="f_right">
			Температура на термометре на<br> основе термопары, установленном на<br> крыше соседнего, 5 учебного корпуса:
			<br>
			<br>
			<a href="http://thermo.karelia.ru/" target="_blank">
				<img id="picture2" style="filter:alpha(opacity=75)" onmouseover="this.filters.alpha.opacity=100" onmouseout="this.filters.alpha.opacity=75" src="http://thermo.karelia.ru/cgi-bin/tb?tid=ptz&hid=2112&bid=1&rnd=1" border="0" width="88" height="31" title="Temperature in Petrozavodsk" alt="Temperature in Petrozavodsk">
			</a>
		</div>
	</div>
</section>
<style>
	body{
		margin: 0;
		padding: 0;
		background-color: #ebebeb;
		font-family: 'Alegreya Sans', sans-serif;
	}
	a{
		color: #000;
	}
	nav{
		color: #fff;
		width: 100%;
		height: 80px;
		background-color: #4b4c4f;
	}
	#logo{
		top: 50%;
		color: #fff;
		left:  -2px;
		float: left;
		width: 155px;
		position: absolute;
		text-align: center;
		white-space: nowrap;
		text-decoration: none;
		transform: translateY(-50%);
	}
	#logo h1{
		margin: 0;
		float: left;
		height: 30px;
		font-size: 38px;
		line-height: 30px;
		display: inline-block;
		text-shadow: 1px 1px 3px #000, 1px 1px 5px #000;
	}
	#logo span{
		float: left;
		font-size: 16px;
		font-weight: 300;
		letter-spacing: 0.2px;
		display: inline-block;
		text-shadow: 1px 1px 3px #000, 1px 1px 2px #000;
	}
	#subtitle{
		top: 50%;
		left: 190px;
		font-size: 24px;
		font-weight: 500;
		position: absolute;
		letter-spacing: 2px;
		text-transform: uppercase;
		transform: translateY(-50%);
	}
	#city{
		top: 50%;
		right: 0px;
		font-size: 24px;
		position: absolute;
		letter-spacing: 2px;
		transform: translateY(-50%);
	}
	.content{
		margin: 0 auto;
		min-height: 80px;
		max-width: 1080px;
		position: relative;
		box-sizing: border-box;
		width: calc(100% - 60px);
	}
	section.content{
		padding: 20px;
		margin: 40px auto;
		background-color: #fff;
	}
	h2{
		margin: 0;
		font-size: 36px;
	}
	h2 span{
		margin-left: 20px;
		font-weight: normal;
	}
	#info{
		width: 100%;
		margin-top: 60px;
		display: inline-block;
	}
	.info{
		float: left;
		width: 33.333%;
		position: relative;
	}
	.info img{
		width: 70%;
		position: absolute;
		transform: translate(-50%, -50%);
	}
	#icon_day{
		left:  50%;
		z-index: 1;
		transform: translate(-50%, -50%);
	}
	#icon_weather{
		left:  30%;
		width: 50%;
		z-index: 2;
		transform: translate(-50%, -20%);
	}
	.i{
		height: 30px;
		font-size: 20px;
		line-height: 30px;
		display: inline-block;
		width: calc(100% - 50px);
	}
	.i b{
		float: right;
		font-family: sans-serif;
	}
	.chart{
		width: 100%;
		margin-top: 60px;
		user-select: none;
		padding-left: 30px;
		position: relative;
		display: inline-block;
		box-sizing: border-box;
	}

	.chart canvas{
		height: 350px!important;
	}

	.chart.mini canvas{
		height: 300px!important;
	}
	.chart span{
		top: 50%;
		left: -90px;
		width: 200px;
		font-size: 22px;
		text-align: center;
		position: absolute;
		letter-spacing: 1px;
		white-space: nowrap;
		text-transform: uppercase;
		transform: rotateZ(-90deg);
	}
	#footer{
		width: 100%;
		font-size: 18px;
		margin-top: 100px;
		display: inline-block;
	}
	#f_left{
		width: 60%;
		float: left;
	}
	#f_right{
		width: 40%;
		float: right;
		text-align: center;
	}
</style>
<link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@100;300;400;500;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
	new Chart(document.getElementById('temperature').getContext('2d'), {
		type: 'line',

		data: {
			labels: JSON.parse('<?=json_encode($temperature_label)?>'),
			datasets: [
				{
					label: 'Температура воздуха',
					backgroundColor: 'rgba(0, 0, 0, 0)',
					borderColor: 'rgba(0, 0, 0, 1)',
					data: JSON.parse('<?=json_encode($temperature_air)?>')
				},{
					label: 'Температура точки росы',
					backgroundColor: 'rgba(0, 0, 0, 0)',
					borderColor: 'rgba(0, 0, 0, .3)',
					data: JSON.parse('<?=json_encode($temperature_dew)?>')
				}
			]
		},

		options: {}
	});


	new Chart(document.getElementById('temperature_ir').getContext('2d'), {
		type: 'line',

		data: {
			labels: JSON.parse('<?=json_encode($temperature_label)?>'),
			datasets: [
				{
					backgroundColor: 'rgba(0, 0, 0, 0)',
					borderColor: 'rgba(229, 62, 62, 1)',
					data: JSON.parse('<?=json_encode($temperature_ir)?>')
				}
			]
		},

		options: {
			legend: {
		        display: false
		    }
		}
	});

	new Chart(document.getElementById('humidity').getContext('2d'), {
		type: 'line',

		data: {
			labels: JSON.parse('<?=json_encode($temperature_label)?>'),
			datasets: [
				{
					backgroundColor: 'rgba(0, 0, 0, 0)',
					borderColor: 'rgba(229, 62, 182, 1)',
					data: JSON.parse('<?=json_encode($humidity)?>')
				}
			]
		},

		options: {
			legend: {
		        display: false
		    }
		}
	});

	new Chart(document.getElementById('illumination').getContext('2d'), {
		type: 'line',

		data: {
			labels: JSON.parse('<?=json_encode($temperature_label)?>'),
			datasets: [
				{
					backgroundColor: 'rgba(0, 0, 0, 0)',
					borderColor: 'rgba(95, 62, 229, 1)',
					data: JSON.parse('<?=json_encode($illumination)?>')
				}
			]
		},

		options: {
			legend: {
		        display: false
		    }
		}
	});

	new Chart(document.getElementById('wind_speed').getContext('2d'), {
		type: 'line',

		data: {
			labels: JSON.parse('<?=json_encode($temperature_label)?>'),
			datasets: [
				{
					backgroundColor: 'rgba(0, 0, 0, 0)',
					borderColor: 'rgba(62, 229, 149, 1)',
					data: JSON.parse('<?=json_encode($wind_speed)?>')
				}
			]
		},

		options: {
			legend: {
		        display: false
		    }
		}
	});
</script>
<script>
	function reload2(){
		var imageObj2 = new Image();
		imageObj2.src='http://thermo.karelia.ru/cgi-bin/tb'+'?tid=ptz&hid=2112&bid=1&rnd='+Math.random();
		document.getElementById('picture2').src=imageObj2.src;

		window.setTimeout(reload2, 27000);
	}

	reload2();
</script>
