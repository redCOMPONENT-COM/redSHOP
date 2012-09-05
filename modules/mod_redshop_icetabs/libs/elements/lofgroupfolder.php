<?php 
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	mod_lofflashcontent
 * @copyright	Copyright (C) JAN 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @license		GNU General Public License version 2
 */
defined('_JEXEC') or die( 'Restricted access' );
/**
 * Get a collection of categories
 */
class JElementLofgroupfolder extends JElement {
	
	/*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'lofgroupfolder';
	
	/**
	 * fetch Element 
	 */
	function fetchElement($name, $value, &$node, $control_name){
		jimport( 'joomla.filesystem.folder' );
		// path to images directory
		$path		= JPATH_ROOT.DS.$node->attributes('directory');
		$filter		= $node->attributes('filter');
		$exclude	= $node->attributes('exclude');
		$folders	= JFolder::folders($path, $filter);
		$options = array ();
		$form = array();
		$mparams = $this->getModuleInfo();
		
		foreach ($folders as $folder)
		{
			if ($exclude)
			{
				if (preg_match( chr( 1 ) . $exclude . chr( 1 ), $folder )) {
					continue;
				}
			}
			$options[] = JHTML::_('select.option', $folder, $folder);
			$tmp = $this->renderForm($folder, $mparams );
			if( $tmp ){
				$f = '<fieldset><legend>'.$folder.'</legend>'.$tmp.'</fieldset>';
				$form[] = $f;
			}
		}
	
	
		if (!$node->attributes('hide_none')) {
			array_unshift($options, JHTML::_('select.option', '-1', '- '.JText::_('Do not use').' -'));
		}

		if (!$node->attributes('hide_default')) {
			array_unshift($options, JHTML::_('select.option', '', '- '.JText::_('Use Default Theme').' -'));
		}
		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name).implode('',$form);
	}
	
	function getModuleInfo(){
		$moduleId = (int)JRequest::getVar('id')?(int)JRequest::getVar('id'):(int)JRequest::getVar('cid');
		//get module as an object
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__modules WHERE id='$moduleId' ");
		$obj = $db->loadObject();	 
		return $obj->params;
	}
	/**
	 * render paramters form
	 *
	 * @return string
	 */
	function renderForm( $theme, $params='', $fileName='params' ){
		// look up configuration file which build-in this plugin or the tempate used.
		$path = dirname(dirname(dirname(__FILE__))).DS.'themes'.DS.$theme.DS.'params.xml';
		if( file_exists($path) ){
			$params = new JParameter(  $params, $path );
			$content = $params->render('params') ;	// echo $content;die;						
			return $content;
		}
		
		return '';
	}
}

?>
