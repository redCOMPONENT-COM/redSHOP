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
class JElementLofCategory extends JElement {
	
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
	function fetchElement($name, $value, &$node, $control_name){
		
		$db = &JFactory::getDBO();
		$version = new 	JVersion();
		
		if( $version->RELEASE == '1.6' ){
			$data =   JHtml::_('category.options', 'com_content');
			$categories = array();
			$categories[0] = new stdClass();
			$categories[0]->value = '';
			$categories[0]->text = JText::_("---------- Select All ----------");
			$data = array_merge($categories,$data);

			return JHTML::_( 'select.genericlist', 
							 $data, ''.$control_name.'['.$name.'][]',
							 'class="inputbox"   multiple="multiple" size="10"',
							 'value', 
							 'text', 
							 $value );
		} else {
		
			$query = 'SELECT * FROM #__sections WHERE published=1';
	
			$db->setQuery( $query );		
			$sections = $db->loadObjectList();
			$categories = array();
			$categories[0]->id = '';
			$categories[0]->title = JText::_("Select All");
			
			foreach ($sections as $section) {
			
			$optgroup = JHTML::_('select.optgroup',$section->title,'id','title');
				$query = 'SELECT id,title FROM #__categories WHERE published=1 AND section='.$section->id;
				$db->setQuery( $query );
				$results = $db->loadObjectList();
				array_push($categories,$optgroup);
				foreach ($results as $result) {
					array_push($categories,$result);
				}
			}
			$optgroup = JHTML::_('select.optgroup',JText::_("Uncategorized"),'id','title');
			array_push($categories,$optgroup);
			$uncategorised=array();
			$uncategorised['id'] = '0';
			$uncategorised['title'] = JText::_("Uncategorized");
			array_push($categories,$uncategorised);
			
			return JHTML::_( 'select.genericlist', 
							 $categories, ''.$control_name.'['.$name.'][]',
							 'class="inputbox" style="width:95%;" multiple="multiple" size="10"',
							 'id', 
							 'title', 
							 $value );
		}
	}
}

?>
