<!DOCTYPE html>
<html>
<meta charset="utf-8">
<title><?php echo $site->_s($config->site_name); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<!-- FAV AND TOUCH ICONS -->
<link rel="shortcut icon" href="bootstrap/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo ROOT_PATH; ?>core/tools/bootstrap/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo ROOT_PATH; ?>core/tools/bootstrap/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo ROOT_PATH; ?>core/tools/bootstrap/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo ROOT_PATH; ?>core/tools/bootstrap/ico/apple-touch-icon-57-precomposed.png">
<!-- FAV AND TOUCH ICONS -->

<!-- JAVASCRIPT FILES -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_PATH; ?>core/tools/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_PATH; ?>core/tools/phptojs.js"></script>
<!-- JAVASCRIPT FILES -->

<!-- CSS FILES -->
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>core/css/themes/smoothness/jquery-ui-1.9.1.custom.min.css">
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>core/tools/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>core/tools/bootstrap/css/bootstrap-responsive.css">
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/styles.layout.css">
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/styles.forms.css">
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/styles.extends.css">
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/styles.fix-bootstrap.css">
<!-- CSS FILES -->

<style>
html, body {
	height: 100%;
}
#wrap {
	min-height: 100%;
}
#main {
	overflow:auto;
	padding-bottom:150px; /* this needs to be bigger than footer height*/
}
.footer {
	position: relative;
	margin-top: -150px; /* negative value of footer height */
	height: 150px;
	clear:both;
	background:#1B1B1B;
} 
#push {
    height: 50px; /* same as the footer */
}
</style>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script type="text/javascript">
$(function(e) {
	$('#dropdown-user-trigger').dropdown()
});
</script>