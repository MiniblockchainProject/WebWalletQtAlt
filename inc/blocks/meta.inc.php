<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
	<title><?php safe_echo($site_name); ?></title>
	
	<link rel="icon" type="image/png" href="./favicon.png" />
	<link rel="apple-touch-icon" href="./img/icons/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="./img/icons/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="./img/icons/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="./img/icons/apple-touch-icon-144x144.png" />
		
	<link rel="stylesheet" href="./css/normalize.css" />
	<link rel="stylesheet" href="./css/boilerplate.css" />
	<link rel="stylesheet" href="./css/bootstrap.min.css" />
	<link rel="stylesheet" href="./css/darkstrap.min.css" />
	<link rel="stylesheet" href="./css/custom.css" />

	<!--[if lt IE 9]>
	<script src="js/html5shiv.min.js"></script>
	<![endif]-->
	<script src="./js/modernizr-2.6.2.min.js"></script>
	<script src="./js/gen_validatorv4.js"></script>
	<script src="./js/jquery-1.11.1.min.js"></script>
	<script src="./js/jquery.form.min.js"></script>
	<script src="./js/jquery.qrcode.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/general.lib.js"></script>
	<script src="./js/base64.lib.js"></script>
	<script src="./js/core.lib.js"></script>
	<script src="./js/plugins.js"></script>

	<script language="JavaScript">
	var page_name = '<?php echo $page; ?>';
	var login_state = '<?php echo $login_state; ?>';
	var l_time = <?php echo (int)($logout_timer*60*1000); ?>;
	var u_time = <?php echo (int)($timer_freq*1000); ?>;
	var update_timer = null;
	var logout_timer = null;
	
    $(document).ready(function() {
	  if (login_state == 'valid') {
        logout_timer = setTimeout(logout, l_time);
	  }
      update_timer = setInterval(update_page, u_time);
      update_page();
    });
	
	$(document).on("mousedown keydown", function(e) {
		clearTimeout(logout_timer);
		logout_timer = setTimeout(logout, l_time);
	});
	</script>
</head>
