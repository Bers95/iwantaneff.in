<?php

// Tastygallery is copyright Vlad Harbuz (vladh.net)

define('TASTY_VERSION','1.0');
define('TASTY_SUBVERSION','1050');

require_once '_tastygallery/common.php';

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set ('magic_quotes_gpc', 0);

?>
<!doctype html>
<html>
	<head>
		<title>effingallery | webdev | html5 | openweb |dev</title>
		<link rel="stylesheet" href="_tastygallery/style.css">
		<link rel="stylesheet" href="_tastygallery/fancybox/jquery.fancybox-1.3.1.css">
	</head>
	<body>
		<div id="everything">
			<div id="header" >
				<h1>effingallery</h1>
			</div>
			<div id="nav">
				
			</div>
			<div id="path" class="clearfix">
				<div id="paths">
					
				</div>
				<div id="slider" class="clearfix">
					<input type="range" min="3" max="10" step="1">
					<div id="slider_jq"></div>
					<div id="slider_n"></div>
					<a id="topinfo"><img src="_tastygallery/images/information.png"></a>
				</div>
			</div>
			<div id="messages">
				
			</div>
			<div id="images" class="clearfix">
				
			</div>
		</div>
		<script>
			var t_dn='<?php echo $t_dn; ?>';
			var t_version='<?php echo TASTY_VERSION; ?>';
			var t_subversion=<?php echo TASTY_SUBVERSION; ?>;
			var t_thumbdir='<?php echo $thumbdir; ?>';
		</script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
		<script src="_tastygallery/fancybox/jquery.fancybox-1.3.1.js"></script>
		<script src="_tastygallery/js/jquery.ba-hashchange.min.js"></script>
		<script src="_tastygallery/js/modernizr-1.5.min.js"></script>
		<script src="_tastygallery/js/jquery.tipsy.js"></script>
		<script src="_tastygallery/js/site.js"></script>
		<script src="http://stats.higg.in/?js"></script>
		<script src="http://stats.higg.in/i/gallery/?js"></script>
	</body>
</html>