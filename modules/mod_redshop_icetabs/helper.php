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

require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';
if( !defined('PhpThumbFactoryLoaded') ) {
	require_once dirname(__FILE__).DS.'libs'.DS.'phpthumb'.DS.'ThumbLib.inc.php';
	define('PhpThumbFactoryLoaded',1);
}
if( !class_exists('LofSliderGroupBase') ){
	require_once( dirname(__FILE__).DS.'libs'.DS.'group_base.php' );
}

abstract class modIceTabsHelper {
	
	/**
	 * get list articles
	 */
	public static function getList( $params ){
		if ( $params->get('enable_cache') ) {
			$cache =& JFactory::getCache('mod_icetabs');
			$cache->setCaching( true );
			$cache->setLifeTime( $params->get( 'cache_time', 15 ) * 60 );	
			return $cache->get( array( 'modIceTabsHelper' , 'getGroupObject' ), array( $params ) ); 
		} else {
			return self::getGroupObject( $params );
		}	
	}
	
	/**
	 * get list articles
	 */
	public static function getGroupObject( $params ){
		$group = $params->get( 'group', 'content' );
		$file = dirname(__FILE__).DS.'libs'.DS.'groups'.DS.strtolower($group).DS.strtolower($group).'.php';	
		if( file_exists($file) ){
			require_once( $file );
			$className = 'LofSliderGroup'.ucfirst($group);
			if( class_exists($className) ){
				$object = new $className( $group );					
				$object->setCurrentPath(  dirname(__FILE__).DS.'libs'.DS.'groups'.DS.strtolower($group).DS );							
			}
		}
		if( $object ){
			return $object->getListByParameters( $params );	
		} else {
			return array();
		}
	}
	

	/**
	 * load css - javascript file.
	 * 
	 * @param JParameter $params;
	 * @param JModule $module
	 * @return void.
	 */
	public static function loadMediaFiles( $params, $module, $theme='' ){
			global $mainframe;
		// if the verion is equal 1.6.x
		if( JVersion::isCompatible('1.6.0') ) {	
			JHTML::script( 'script_16.js','modules/'.$module->module.'/assets/');
		} else {
			JHTML::script( 'script_15.js','modules/'.$module->module.'/assets/');
		}
				
		if( $theme && $theme != -1 ){
			$tPath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'css'.DS.$module->module.'_'.$theme.'.css';
			if( file_exists($tPath) ){
				JHTML::stylesheet( $module->module.'_'.$theme.'.css','templates/'.$mainframe->getTemplate().'/css/');
			} else {
				JHTML::stylesheet('style.css','modules/'.$module->module.'/themes/'.$theme.'/assets/');	
			}
		} else {
			JHTML::stylesheet( 'style.css','modules/'.$module->module.'/assets/' );	
		}
		// load js of modalbox
		if( $params->get('load_jslibs','modal') && !defined('LOF_ADDED_MODALBOX') && $params->get('open_target','')== 'modalbox' ){
				$doc =& JFactory::getDocument();
				$string  = '<script type="text/javascript">';
				$string .= "
					var box = {};
					window.addEvent('domready', function(){
						box = new MultiBox('mb', {  useOverlay: false,initialWidth:1000});
					});
				";
				$string .= '</script>';
				$doc->addCustomTag( $string );
				JHTML::stylesheet( 'multibox.css','modules/'.$module->module.'/assets/multibox/');
				JHTML::script( 'multibox.js','modules/'.$module->module.'/assets/multibox/');
				JHTML::script( 'overlay.js','modules/'.$module->module.'/assets/multibox/');
		}
	}
	
	/**
	 *
	 */
	public function renderItem( &$row, $params, $layout='_item' ){
		$target = $params->get('open_target','_parent') != 'modalbox'
							? 'target="'.$params->get('open_target','_parent').'"'
							: 'rel="'.$params->get('modal_rel','width:800,height:350').'" class="mb"'; 
							
		$path = dirname(__FILE__).DS.'themes'.DS.$params->get('theme').DS;
		if( file_exists($path.$params->get('group').$layout.'.php') ){
			require( $path.$params->get('group').$layout.'.php' );
			return ;
		}
		require( $path.$layout.'.php' );
	}
	
	/**
	 * load theme
	 */
	public static function getLayoutByTheme( $module, $group, $theme= '' ){
		global $mainframe;
		$layout = '';
		if( $theme ) {
			$layout = $group.DS.trim($theme).'_default';	
		}
		
		// Build the template and base path for the layout
		$tPath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$module->module.DS.$layout.'.php';
		$bPath = JPATH_BASE.DS.'modules'.DS.$module->module.DS.'tmpl'.DS.$layout.'.php';

		// If the template has a layout override use it
		if (file_exists($tPath)) {
			return $tPath;
		} elseif( file_exists($bPath) ) {
			return $bPath;
		}
		return JPATH_BASE.DS.'modules'.DS.$module->module.DS.'themes'.DS.$theme.DS.'default.php';
	}
}
?>
