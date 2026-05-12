<?php
if($_SERVER['HTTP_HOST'] != "m.jbsilver.net") {
?>

<?php if (defined("_INDEX_")) { ?><div style="text-align:center;color:#fff;padding-bottom:20px;">Copyright (C) 2019 jbsilver.net All Rights Reseved.
<?php
	if(G5_DEVICE_BUTTON_DISPLAY && G5_IS_MOBILE) { ?>
	<a href="<?php echo get_device_change_url(); ?>" class="mobile_btn">PC버전</a>
	<?php
	}

	if ($config['cf_analytics']) {
		echo $config['cf_analytics'];
	}
	?>
</div><?php } ?>


<?php if (!defined("_INDEX_")) { ?><div style="text-align:center;color:#333;padding:20px 0;border-top:1px solid #dddddd;">Copyright (C) 2019 jbsilver.net All Rights Reseved.
<?php
	if(G5_DEVICE_BUTTON_DISPLAY && G5_IS_MOBILE) { ?>
	<a href="<?php echo get_device_change_url(); ?>" class="mobile_btn">PC버전</a>
	<?php
	}

	if ($config['cf_analytics']) {
		echo $config['cf_analytics'];
	}
	?>
</div>
</div><?php } ?>

<?php }?>

