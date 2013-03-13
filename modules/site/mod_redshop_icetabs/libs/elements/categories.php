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
class JFormFieldCategories extends JFormField
{
    var $_name = 'Categories';

    function getInput()
    {
        $db 		= &JFactory::getDBO();
		$attributes = $this->element;
		$parent_id  = $attributes['parent'];
        $class    	= $attributes['class'];
		
        if(!$class)
		{
            $class = "inputbox";
        }
        $query = 'SELECT c.id AS value, c.title AS text' .
				' FROM #__categories AS c' .
				' WHERE c.published = 1' .
				' AND c.parent_id = '.$db->Quote($parent_id).
				' ORDER BY c.title';
				
        $db->setQuery($query);
        $options 	= $db->loadObjectList();
		$categories = array();
		
		$categories[0] = new stdClass();
		$categories[0]->value = '';
		$categories[0]->text = JText::_("---------- Select All ----------");
		$options = array_merge($categories,$options);	
        return JHTML::_('select.genericlist',  $options, ''.$this->name.'[]', 'class="inputbox" size="5" style="width:95%;" multiple="multiple"', 'value', 'text', $this->value, $this->fieldname);
    }
}