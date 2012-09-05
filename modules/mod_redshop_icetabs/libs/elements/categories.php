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
class JElementCategories extends JElement {
	
    var $_name = 'Categories';

    function fetchElement($name, $value, &$node, $control_name)
    {	
        $db = &JFactory::getDBO();
		
        $section  = $node->attributes('section');
        $class    = $node->attributes('class');
        if (!$class) {
            $class = "inputbox";
        }

        if (!isset ($section)) {
            // alias for section
            $section = $node->attributes('scope');
            if (!isset ($section)) {
                $section = 'content';
            }
        }

        if ($section == 'content') {
            $query = 'SELECT c.id AS value, CONCAT_WS( "/",s.title, c.title ) AS text' .
                    ' FROM #__categories AS c' .
                    ' LEFT JOIN #__sections AS s ON s.id=c.section' .
                    ' WHERE c.published = 1' .
                    ' AND s.scope = '.$db->Quote($section).
                    ' ORDER BY s.title, c.title';
        } else {
            $query = 'SELECT c.id AS value, c.title AS text' .
                    ' FROM #__categories AS c' .
                    ' WHERE c.published = 1' .
                    ' AND c.section = '.$db->Quote($section).
                    ' ORDER BY c.title';
        }
        $db->setQuery($query);
        $options = $db->loadObjectList();
		$categories = array();
		$categories[0] = new stdClass();
		$categories[0]->value = '';
		$categories[0]->text = JText::_("---------- Select All ----------");
		$options = array_merge($categories,$options);
					
        return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]',
                      'class="inputbox" size="5" style="width:95%;" multiple="multiple"',
                      'value', 'text', $value, $control_name.$name);
    }
}
?>