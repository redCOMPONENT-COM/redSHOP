<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products_slideshow
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

?>
<div class="slideshow-stage">
	<script language="javascript">AC_FL_RunContent = 0;</script>
	<script src="<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/assets/AC_RunActiveContent.js"
			language="javascript"></script>
	<script language="javascript">
		if (AC_FL_RunContent == 0)
		{
			alert("This page requires AC_RunActiveContent.js.");
		}
		else
		{
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0',
				'width', '<?php echo $bannerWidth; ?>',
				'height', '<?php echo $bannerHeight; ?>',
				'src', '<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/assets/slideshow',
				'quality', 'high',
				'pluginspage', 'http://www.adobe.com/go/getflashplayer_cn',
				'align', 'middle',
				'play', 'true',
				'loop', 'true',
				'scale', 'showall',
				'wmode', '<?php echo $wmode; ?>',
				'devicefont', 'false',
				'flashvars','url=<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/assets/data_<?php echo $module->id; ?>.xml',
				'id', 'AnimatedLines',
				'bgcolor', '<?php echo $backgroundColor; ?>',
				'name', 'AnimatedLines',
				'menu', 'true',
				'allowFullScreen', 'false',
				'allowScriptAccess','sameDomain',
				'movie', '<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/assets/slideshow',
				'salign', ''
			);
		}
	</script>
	<noscript>
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
				codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
				width="<?php echo $bannerWidth; ?>"
				height="<?php echo $bannerHeight; ?>" id="AnimatedLines" align="middle">
			<param name="allowScriptAccess" value="sameDomain"/>
			<param name="allowFullScreen" value="false"/>
			<param name="flashvars"
				   value="url=modules/mod_redshop_products_slideshow/assets/data_<?php echo $module->id; ?>.xml"/>
			<param name="movie" value="<?php echo JURI::root()?>modules/mod_redshop_products_slideshow/assets/slideshow.swf"/>
			<param name="quality" value="high"/>
			<param name="bgcolor" value="<?php echo $backgroundColor; ?>"/>
			<embed
				src="<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/assets/slideshow.swf"
				flashvars="url=modules/mod_redshop_products_slideshow/assets/data_<?php echo $module->id; ?>.xml"
				quality="high"
				bgcolor="<?php echo $backgroundColor; ?>"
				width="<?php echo $bannerWidth; ?>"
				height="<?php echo $bannerHeight; ?>" name="AnimatedLines"
				align="middle" allowScriptAccess="sameDomain" allowFullScreen="false"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflashplayer_cn"/>
		</object>
	</noscript>
</div>
