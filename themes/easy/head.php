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
    <?php $load->require("assets", "head.php"); ?>
<?php 
    $jss = Gp::data()->get('head.js');
    if (is_array($jss) && count($jss) > 0) {
        foreach ($jss  as $js) {
            if ($js != "") {
                ?><script type="text/javascript" src="<?php echo $js; ?>"></script>
        <?php
            }
        } 
    }
    $csss = Gp::data()->get('head.css');
    if (is_array($csss) && count($csss) > 0) {
        foreach ($csss  as $css) {
            if ($css != "") {
                ?><link rel="stylesheet" type="text/css" href="<?php echo $css; ?>" media="screen,print">
        <?php
            }
        } 
    }
?>
<script>var _LINK_AJAX = "<?php echo Gp::route()->getLink('?page=ajax'); ?>";</script>
<?php 
	$scripts = Gp::data()->get('head.script');
	if (is_array($scripts) && count($scripts) > 0) {
		?><script>
		<?php
		foreach ($scripts as $script) {
			if ($script != "") {
				$script = str_replace(array("<script>","</script>"), $script);
				echo $script."\n";
			}
		} 
		?>
		</script>
		<?php
	}
	$styles = Gp::data()->get('head.style');
	if (is_array($styles) && count($styles) > 0) {
		?><style>
		<?php
		foreach ($styles as $style) {
			if ($style != "") {
				$stye = str_replace(array("<style>","</style>"), $style);
				echo $style."\n";
			}
		} 
		?>
		</style>
		<?php
	}
?>
</head>
