<?php 
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	mod_lofflashcontent
 * @copyright	Copyright(C) JAN 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @license		GNU General Public License version 2
 */
defined('_JEXEC') or die('Restricted access');
/**
 * Get a collection of categories
 */ 	
class JFormFieldFgroup extends JFormField
{
	/*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'fgroup';
	/**
	 * fetch Element 
	 */
	function getInput()
	{
		$mediaPath = JURI::root(). str_replace(DS, '/', str_replace(JPATH_ROOT, '',dirname(dirname(dirname(__FILE__))))).'/assets/';
		JHTML::stylesheet($mediaPath .'form.css');
		$attributes = $this->element;
		$class 		= isset($attributes['group']) && trim($attributes['group']) == 'end' ? 'lof-end-group' : 'lof-group'; 
		$title		=  isset($attributes['title']) ?  JText::_($attributes['title']):'';
		$for 		= isset($attributes['for'])?$attributes['for']:'';
		$string 	= '<div '.($title?"":'style="display:none"').'  class="'.$class.'" title="'.$for.'">'.$title.'</div>';
		
		if(!defined('LOF_ADDED_TIME'))
		{
			$string .= '<input type="hidden" class="text_area" value="'.time().'" id="jform_params_lof_added_time" name="jform[params][lof_added_time]">';
			define('LOF_ADDED_TIME',1);
		}

		if(!defined('ADD_MEDIA_CONTROL'))
		{
			define('ADD_MEDIA_CONTROL',1);
			$uri = str_replace(DS,"/",str_replace(JPATH_SITE, JURI::base(), dirname(__FILE__)));
			$uri = str_replace("/administrator/", "", $uri);

			JHTML::stylesheet($uri."/media/form.css");
			JHTML::script($uri."/media/form.js");
		}
		return $string;
	}
}