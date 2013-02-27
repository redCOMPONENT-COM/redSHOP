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
defined('_JEXEC') or die('Restricted access');
/**
 * Get a collection of categories
 */
class JFormFieldLofCategory extends JFormField {
	
	/*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'LofCategory';
	
	/**
	 * fetch Element 
	 */
	function getInput()
	{
		$db 	= &JFactory::getDBO();
		$data 	= JHtml::_('category.options', 'com_content');
		$categories 	= array();
		$categories[0] 	= new stdClass();
		$categories[0]->value 	= '';
		$categories[0]->text 	= JText::_("---------- Select All ----------");
		$data = array_merge($categories,$data);

		return JHTML::_('select.genericlist', $data, ''.$this->name.'[]', 'class="inputbox"   multiple="multiple" size="10"', 'value', 'text', $this->value,$this->id);
	}
}