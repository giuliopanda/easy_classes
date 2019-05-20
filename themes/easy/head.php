<head>
	<?php $load = GpLoad::getInstance(); ?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title> </title>
	<meta name="googlebot" content="noindex, noarchive, nofollow, nosnippet">
	<meta name="robots" content="noindex, noarchive, nofollow">
	<meta name="slurp" content="noindex, noarchive, nofollow, noodp, noydir">
	<meta name="msnbot" content="noindex, noarchive, nofollow, noodp">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?php echo $load->getUri('theme', '/assets/favicon.ico'); ?>" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="<?php echo $load->getUri('theme', 'assets/bootstrap.min.css'); ?>" media="screen,print">
	<link rel="stylesheet" type="text/css" href="<?php echo $load->getUri('theme', 'assets/easy.css'); ?>" media="screen,print">
	<script type="text/javascript" src="<?php echo $load->getUri('theme', 'assets/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo $load->getUri('theme', 'assets/bootstrap.bundle.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo $load->getUri('theme', 'assets/easy.js'); ?>"></script>
	<script>
		var _LINK_AJAX = "<?php echo Gp::route()->getLink('?page=ajax'); ?>";
	</script>
</head>