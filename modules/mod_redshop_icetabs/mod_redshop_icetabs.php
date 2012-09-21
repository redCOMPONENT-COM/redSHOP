<?php
/**
 * IceTabs Module for Joomla 1.6 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2011 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/icetabs.html
 * @Support 	http://www.icetheme.com/Forums/IceTabs/
 *
 */
 
// no direct access
defined('_JEXEC') or die;


// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';
$list = modIceTabsHelper::getList($params);

$group 			= $params->get('group','content');
$tmp 		 	= $params->get('module_height', 'auto');
$moduleHeight   = ($tmp=='auto') ? 'auto' : (int)$tmp.'px';
$tmp 			= $params->get('module_width', 'auto');
$moduleWidth    = ($tmp=='auto') ? 'auto': (int)$tmp.'px';
$themeClass 	= $params->get('theme' , 'candy');
$openTarget 	= $params->get('open_target', 'parent');
$class 			= !$params->get('navigator_pos', 0) ? '':'ice-'.$params->get('navigator_pos', 0);
$theme		    = $params->get('theme', ''); 
$showReadmore 	= $params->get('show_readmore',1);
$params->set('item-content', 'desc-image');
$params->set('replacer', '...');
$itemContent	= $params->get('item-content','desc-image');

$navPos			= $params->get('navigator_pos', 0);
$navwidth		= (int)$params->get('navitem_width', 290);
$navheight		= (int)$params->get('navitem_height', 100);
$main_height	= (int)$params->get('main_height', 300);
switch($navPos)
{
	case "left":
		$navcss		= "height:".$navheight."px;";
		$maincss	= "margin-left:".$navwidth."px;";
	break;	
	case "bottom":
		$navcss		= "margin-top:".$main_height."px";
		$maincss	= "";
	break;
	case "top":
	case "right":
	default:
		$navcss		= "height:".$navheight."px;";
		$maincss	= "";
	break;
}

// load custom theme
if($theme && $theme != -1) {
	require(modIceTabsHelper::getLayoutByTheme($module, $group, $theme));
} else {
	require(JModuleHelper::getLayoutPath($module->module));	
}
modIceTabsHelper::loadMediaFiles($params, $module, $theme);
?>
<script type="text/javascript">
	var _lofmain = $('icetabs<?php echo $module->id; ?>'); 
	var object = new IceSlideShow(_lofmain.getElement('.ice-main-wapper'), 
								  _lofmain.getElement('.ice-navigator-outer .ice-navigator'),
								  _lofmain.getElement('.ice-navigator-outer'),
								  { 
									  fxObject:{
										transition:<?php echo $params->get('effect', 'Sine.easeInOut');?>,  
										duration:<?php echo (int)$params->get('duration', '700')?>
									  },
									  mainItemSelector: 'div.ice-main-item',
									  interval:<?php echo (int)$params->get('interval', '3000'); ?>,
									  direction :'<?php echo $params->get('layout_style','opacity');?>', 
									  navItemHeight:<?php echo $params->get('navitem_height', 100) ?>,
									  navItemWidth:<?php echo $params->get('navitem_width', 290) ?>,
									  navItemsDisplay:<?php echo $params->get('max_items_display', 3) ?>,
									  navPos:'<?php echo $params->get('navigator_pos', 0); ?>'
								  });
	<?php if($params->get('display_button', '')): ?>
		object.registerButtonsControl('click', {next:_lofmain.getElement('.ice-next'),previous:_lofmain.getElement('.ice-previous')});
	<?php endif; ?>
		object.start(<?php echo $params->get('auto_start',1)?>, _lofmain.getElement('.preload'));
</script>