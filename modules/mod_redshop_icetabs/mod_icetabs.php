<?php
/**
 * $ModDesc
 *
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) Dec 2009 IceTheme.com.All rights reserved.
 * @license		GNU General Public License version 2
 * -------------------------------------
 * Based on Module Libs From LandOfCoder
 * @copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com, @site: http://landofcoder.com>.
 */
// no direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';

$option=JRequest::getVar('option');
if($option!='com_redshop')
{
	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
	require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php' );
	$Redconfiguration = new Redconfiguration();
	$Redconfiguration->defineDynamicVars();
}
$list = modIceTabsHelper::getList( $params );

$group 			= $params->get( 'group','redshop' );
$tmp 		 	= $params->get( 'module_height', 'auto' );
$moduleHeight   =  ( $tmp=='auto' ) ? 'auto' : (int)$tmp.'px';
$tmp 			= $params->get( 'module_width', 'auto' );
$moduleWidth    =  ( $tmp=='auto') ? 'auto': (int)$tmp.'px';
$themeClass 	= $params->get( 'theme' , '');
$openTarget 	= $params->get( 'open_target', 'parent' );
$class 			= !$params->get( 'navigator_pos', 0 ) ? '':'ice-'.$params->get( 'navigator_pos', 0 );
$theme		    =  $params->get( 'theme', 'default-white' );


// load custom theme
if( $theme && $theme != -1 ) {
	require( modIceTabsHelper::getLayoutByTheme($module, $group, $theme) );
} else {
	require( JModuleHelper::getLayoutPath($module->module) );
}
modIceTabsHelper::loadMediaFiles( $params, $module, $theme );

?>
<script type="text/javascript">
	var _lofmain =  $('icetabs<?php echo $module->id; ?>');
	var object = new LofSlideshow( _lofmain.getElement('.ice-main-wapper'),
								  _lofmain.getElement('.ice-navigator-outer .ice-navigator'),
								  _lofmain.getElement('.ice-navigator-outer'),
								  {
									  fxObject:{
										transition:<?php echo $params->get( 'effect', 'Sine.easeInOut' );?>,
										duration:<?php echo (int)$params->get('duration', '700')?>
									  },
									  interval:<?php echo (int)$params->get('interval', '3000'); ?>,
									  direction :'<?php echo $params->get('layout_style','opacity');?>',
									  navItemHeight:<?php echo $params->get('navitem_height', 100) ?>,
									  navItemWidth:<?php echo $params->get('navitem_width', 290) ?>,
									  navItemsDisplay:<?php echo $params->get('max_items_display', 3) ?>,
									  navPos:'<?php echo $params->get( 'navigator_pos', 0 ); ?>'
								  } );
	<?php if( $params->get('display_button', '') ): ?>
		object.registerButtonsControl( 'click', {next:_lofmain.getElement('.ice-next'),previous:_lofmain.getElement('.ice-previous')} );
	<?php endif; ?>
		object.start( <?php echo $params->get('auto_start',1)?>, _lofmain.getElement('.preload') );
</script>
