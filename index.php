<?php header('Content-type: text/html; charset=utf-8');
?>
<?php
	include_once("options.php");
	include_once("functions.php");
	$phpVersion = phpversion();
	
	$LangDir=$_COOKIE["LANG"];  //directory for language from cookie
	isInList($LangDir, $availableLanguages);
	
	$CookieLang=$_GET["lang"];  //language for setting cookie
	$CookieLang = strtolower($CookieLang);
	if ($CookieLang=="")
	{
		$CookieLang=$LangDir;
	}
	else
	{
		isInList($CookieLang, $availableLanguages);
		$LangDir=$CookieLang;
	}
	
	$expire=time()+3600*24*365*10;         //setting cookie time
	setcookie("LANG", $CookieLang, $expire); //setting cookie
	
	$PageToView =$_GET["page"];  //current page
	
	isInList($PageToView, $availablePages);

	$MenuLines = file("./Lang/".$LangDir."/Menu.txt");
	for($i = 0; $i < count($MenuLines); $i++)
		$MenuLines[$i] = trim($MenuLines[$i]);
		
	$ServerName = $_SERVER['HTTP_HOST'];//адреса сервера
	$PHP_EOL = strtoupper(substr(PHP_OS,0,3) == 'WIN') ? "\r\n" : "\n";
	$xmlHtmlLang = strtolower($LangDir);
	if ($xmlHtmlLang[1] == 'a')
		$xmlHtmlLang[1] = 'k';
	
	$output = "";
	$keywords = "";
	$description = "";
	$title = "";
	
	//include optins(variables) for current language
	include_once("./Lang/" . $LangDir . "/" . "options.php");
	
	$titleLocal = "";
	$descriptionLocal = "";
	$keywordsLocal = "";
	
	//parsing content...
	//read entire file content into string variable AND parse it
	$fileContents = file_get_contents("./Lang/" . $LangDir . "/" . $PageToView . ".txt");
	
	//Turn on output buffering
	ob_start();
	
	//process $fileContents with PHP interpreter and place it to the output
	eval("?>" . $fileContents);
	
	//assign the contents of the output buffer to the $fileContents
	$fileContents = ob_get_contents();
	
	//Clean (erase) the output buffer and turn off output buffering
	ob_end_clean();
	
	if($phpVersion[0] >= 5)
	{
		$parseCodeStr="
			try
			{
				\$parsedData = parseInputString(\$fileContents, \$pageParseTags);
				if(\$parsedData[\"content\"] == \"\")
					\$parsedData[\"content\"] = \$parsedData[\"remained\"];
				if(\$parsedData[\"desription\"] == \"\")
					\$parsedData[\"desription\"] = trim(strip_tags(substr(\$parsedData[\"content\"], 0, \$dotPos = strpos(\$parsedData[\"content\"], '.')===FALSE?\$contentLen = strlen(\$parsedData[\"content\"])>56?56:\$contentLen:\$dotPos)));
				\$output = \$parsedData[\"content\"];
				\$keywordsLocal .= \$parsedData[\"keywords\"];
				\$descriptionLocal .= \$parsedData[\"description\"];
				\$titleLocal = \$parsedData[\"title\"];
			}
			catch (Exception \$e)
			{
				\$output .= \$parseException .= \"Cought exception: \" . \$e->GetMessage();
			}";
		eval($parseCodeStr);
	}
	else
	{
		$parsedData = parseInputString($fileContents, $pageParseTags);
		if($parsedData[exception] == "")
		{
			if($parsedData["content"] == "")
				$parsedData["content"] = $parsedData["remained"];
			if($parsedData["desription"] == "")
				$parsedData["desription"] = trim(strip_tags(substr($parsedData["content"], 0, $dotPos = strpos($parsedData["content"], '.')===FALSE?$contentLen = strlen($parsedData["content"])>56?56:$contentLen:$dotPos)));
			$output = $parsedData["content"];
			$keywordsLocal .= $parsedData["keywords"];
			$descriptionLocal .= $parsedData["description"];
			$titleLocal = $parsedData["title"];
		}
		else
			$output .= "Exception appeared: " . $parsedData[exception];
	}
?>
<?php
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<!DOCTYPE html>

<html>

	<head>
		<?php
			if ($PageToView=="map")
			{
				echo "<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=false\"></script>$PHP_EOL";
				echo "<script type=\"text/javascript\" src=\"scripts/maps.js\"></script>$PHP_EOL";
			}
		?>

		<script type="application/ld+json">
			{
				"@context": "http://schema.org/",
				"@type": "Hotel",
				"name": "Villa Leonardo",
				"logo": "",
				"image": "http://www.agencyleonard.com/photo/rotator/dom_b.jpg",
				"description": "Two-story building of the villa is situated in the small picturesque resort village Zelenika not far from the most popular resort of Montenegro Herzeg - Novi city. Your rest here will differ greatly from mass tourism at nearby areas. You will enjoy the unique local nature and fill its favorable influence.",
				"priceRange" : "15 - 42А per night",
				"address" : "Montenegro, Zelenika, 85346",
				"email": "info@agencyleonard.com",
				"foundingDate" : "2008-04-09T21:00:00.000Z",
				"telephone" : "+38 (269) 272-431",
				"map" :"https://www.google.com.ua/maps/@42.4512979,18.5782905,180m/data=!3m1!1e3"
			}
		</script>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){
				removeHoverCSSRule();
				$(window).scroll(function(){
					if ($(this).scrollTop() > 100) {
						$('.scrollup').fadeIn();
					} else {
						$('.scrollup').fadeOut();
					}
				});
				$('.scrollup').click(function(){
					$("html, body").animate({ scrollTop: 0 }, 600);
					return false;
				});
			});

			function removeHoverCSSRule() {
				if ('createTouch' in document) {
					try {
						var ignore = /:hover/;
						for (var i = 0; i < document.styleSheets.length; i++) {
							var sheet = document.styleSheets[i];
							if (!sheet.cssRules) {
								continue;
							}
							for (var j = sheet.cssRules.length - 1; j >= 0; j--) {
								var rule = sheet.cssRules[j];
								if (rule.type === CSSRule.STYLE_RULE && ignore.test(rule.selectorText)) {
									sheet.deleteRule(j);
								}
							}
						}
					}
					catch(e) {
					}
				}
}
		</script>

		<!-- AddThis Smart Layers BEGIN -->
		<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
		<script type="text/javascript" 
		src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5242c4e06b721066"></script>
		<script type="text/javascript">
			addthis.layers({
				'theme' : 'transparent',
				'share' : {
					'position' : 'left',
					'numPreferredServices' : 7,
					'services' : 'facebook,vk,twitter,odnoklassniki_ru,myspace,google_plusone_share,more',
				}
			});
		</script>
		<!-- AddThis Smart Layers END -->

		<meta property="fb:admins" content="100001502171999,100001347304015"/>
		<meta property="fb:app_id" content="606407436068849"/> 

		<div id="fb-root"></div>
		<script type="text/javascript" src="scripts/facebookWall.js"></script>


		<div id="fb-root"></div>
		<script>
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/uk_UA/all.js#xfbml=1";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>

		<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script> -->
		<script type="text/javascript" src="scripts/slimbox2.js"></script>
		
		<!-- <script type="text/javascript" src="scripts/allversion.js"></script> - Modernizr-->
		<!-- <link href='http://fonts.googleapis.com/css?family=Comfortaa:400,700&subset=cyrillic-ext,latin' rel='stylesheet' type='text/css'>  Font-->
		<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />
		<meta name="google-site-verification" content="vwQrVWduoy9SQBfbmcYZjavjmvv2oxk81W-P6AxRwtQ" />
		<meta name="msvalidate.01" content="FD4B53F5D9C730B40F88ED989FAEBE9F" />  
		<meta name="y_key" content="98a59c7229d23a0b" />
		<meta name="yandex-verification" content="6440fb0bd40494f4" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta name="keywords" content="<?php echo $keywords; if(($keywordsLocal != "")&&($keywords != "")) echo ", "; echo $keywordsLocal; ?>" />
		<meta name="Description" content="<?php echo $description; if(($descriptionLocal != "")&&($description != "")) echo " "; echo $descriptionLocal; ?>" />
		<title><?php echo $title; if(($titleLocal != "")&&($title != "")) echo " - "; echo $titleLocal; ?></title>
		<link rel="stylesheet" href="css/main.css"/>
		<link rel="stylesheet" href="css/menu.css"/>
		<link rel="stylesheet" href="css/mailform.css"/>
		<link rel="stylesheet" href="css/lang_bar.css"/>
		<link rel="stylesheet" href="dd-formmailer/dd-formmailer.css"/>
		<link rel="stylesheet" href="css/responsive.css">
		<!--[if gte IE 5.5]>
		<![if lt IE 7]>
		<style type="text/css">
		div.fixedBlock{
		/* IE5.5+/Win - трошки ≥накше н≥ж IE 5.0 верс≥њ */
		right: auto; bottom: auto;
		left: expression( ( -20 - fixedBlock.offsetWidth + ( document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth ) + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
		top: expression( ( -10 - fixedBlock.offsetHeight + ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
		}
		</style>
		<![endif]>
		<![endif]--> 

		<!-- for google-analytics -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-16583477-2']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
		<!-- end for google-analytics -->
	</head>
	<body <?php if ($PageToView=="map")  echo "onload=\"initialize()\"";?>>
		
		<div id="LangBar" class=".fixedBlock">
			<div id="language_bar">
				<ul>
					<?php 
						foreach($availableLanguages as $i) {
							echo "
									<li style=\"background-image: url(./img/flags/"; if($i == $LangDir)	echo 'H'; echo strtoupper($i); echo ".png);\">
										<a "; if ($i == $LangDir) echo "style=\"visibility: hidden;\"";	echo " href=\"?lang=$i&amp;page=$PageToView\"></a>
									</li>";
						}
						echo "";
					?>
				</ul>
			</div>
		</div>

<!--
	<div id="weather" style="width: 120px; height: 60px; background-image: url( http://vortex.accuweather.com/adcbin/netweather_v2/backgrounds/green_120x60_bg.jpg ); background-repeat: no-repeat; background-color: #336633;" >
		<div id="NetweatherContainer" style="height: 48px;" >
			<script src="http://netweather.accuweather.com/adcbin/netweather_v2/netweatherV2ex.asp?partner=netweather&tStyle=whteYell&logo=0&zipcode=EUR|CS|YI002|ZELENIKA|&lang=eng&size=7&theme=green&metric=1&target=_self"></script>
		</div>
		<div style="text-align: center; font-family: arial, helvetica, verdana, sans-serif; font-size: 10px; line-height: 12px; color: #FFFFFF;" >
			<a style="color: #FFFFFF" href="http://www.accuweather.com/world-index-forecast.asp?partner=netweather&locCode=EUR|CS|YI002|ZELENIKA|&metric=1" >Weather Forecast</a>
		</div>
	</div>

-->
	<style>
	@media screen and (max-width: 1000px){
		#weather {
			display:none;
		}
	}
	</style>

<!-- 	<div id="weather" style='width: 180px; 
		height: 150px; 
		background-image: url( http://vortex.accuweather.com/adcbin/netweather_v2/backgrounds/summer2_180x150_bg.jpg ); 
		background-repeat: no-repeat; 
		background-color: #D0ADAA;' >

	<div id='NetweatherContainer' style='height: 138px;' >
		<script src='http://netweather.accuweather.com/adcbin/netweather_v2/netweatherV2ex.asp?partner=netweather&tStyle=normal&logo=1&zipcode=EUR|CS|YI002|ZELENIKA|&lang=uke&size=8&theme=summer2&metric=1&target=_self'>
		</script>
	</div>

	<div style='text-align: center; 
	font-family: arial, helvetica, verdana, sans-serif; 
	font-size: 10px; 
	line-height: 12px; 
	color: #0000FF;' >
		<a style='color: #0000FF' 
			href='http://www.accuweather.com/world-index-forecast.asp?partner=netweather&locCode=EUR|CS|YI002|ZELENIKA|&metric=1' >Weather Forecast</a>†|†<a 
			style='color: #0000FF' href='http://www.accuweather.com/maps-satellite.asp' >Weather Maps</a>
	</div>
</div> -->

	<div id="weather">
		<a href="http://www.accuweather.com/en/me/herceg-novi/298280/weather-forecast/298280" class="aw-widget-legal"></a>
		<div id="awcc1433937655083"
			class="aw-widget-current" 
			data-locationkey="298280" 
			data-unit="c" 
			data-language="en-us" 
			data-useip="false" 
			data-uid="awcc1433937655083">
		</div>
		<script type="text/javascript" src="http://oap.accuweather.com/launch.js"></script>
	</div>
<!-- 
<p style="display: block !important; 
width: 160px; 
text-align: center; 
font-family: sans-serif; 
font-size: 12px;">
<a href="http://weathertemperature.com/forecast/?q=Zelenika,Montenegro" 
title="Zelenika, Montenegro Weather Forecast" 
onclick="this.target='_blank'">

<img src="http://widget.addgadgets.com/weather/v1/?q=Zelenika,Montenegro&amp;s=2&amp;u=1" 
alt="Weather temperature in Zelenika, Montenegro" width="160" height="102" style="border:0"></a>
<br>
<a href="http://weathertemperature.com/" 
title="Get latest Weather Forecast updates" 
style="font-family: sans-serif; font-size: 12px" 
onclick="this.target='_blank'">Weather Forecast</a></p> -->


<!-- <a href="//www.booked.net/weather/Herceg-Novi-w230295">
	<img src="//w.bookcdn.com/weather/picture/32_w230295_1_1_3498db_250_2980b9_ffffff_ffffff_1_2071c9_ffffff_0_3.png?scode=124&domid=" />
</a> -->



	<div id="topname"></div>
	<div id="top"></div>
	<div id="menu">
		<div id="nav-menu">
			<ul>
				<?php
					$k = 0;
					foreach($availablePages as $ap) {
						echo "<li>
								<a "; if($ap == $PageToView) echo "id=\"current_page\" "; echo "href=\"?page=$ap\">"; echo $MenuLines[$k++]; echo "</a>
							</li>";
						if ($MenuLines[$k+1]!="") {
							echo "<li class=\"seperator\" style=\"height: 38px;\"></li>";
						}
					}
					echo "";
				?>
			</ul>
		</div>
	</div>

	<div id="content">
		<div id="top_decor"></div>
		<div id="middle_decor">
			<div id="content_text">
				<?php
					echo $output;
				?>
			</div>
		</div>
		<div id="bottom_decor"></div>
	</div>

	<div id="bottom_menu">
		<div id="bottom-menu">
			<ul>
				<?php
					$k = 0;
					foreach($availablePages as $ap) {
						echo "<li>
								<a "; if ($ap == $PageToView) echo "id=\"up_button\" href=\"#top\">".$MenuLines[5]; else echo "href=\"?page=$ap\">".$MenuLines[$k]; echo "</a>
							</li>";
						$k++;
					}
					echo "";
				?>
			</ul>
		</div>
	</div>


		
	<div style="margin:20px auto; clear: both;">
		<a href="http://info.flagcounter.com/tgoy">
		<img src="http://s07.flagcounter.com/count/tgoy/bg_EBEBE4/txt_000000/border_CCCC37/columns_8/maxflags_16/viewers_3/labels_1/pageviews_1/flags_0/" 
			alt="Flag Counter" border="0"></a>
	</div>

	<div id="footer">
		<p>©2010<?php $date_array = getdate(); if ($date_array[year]>2010) echo ("-".$date_array[year])?> Hotel Leonardo</p>
		<!-- <div id="unikernel">
			<a href="http://www.unikernel.net/">
				© 2010 UniKernel IT Development Team
			</a>
		</div> -->

	</div>
	</body>
</html>