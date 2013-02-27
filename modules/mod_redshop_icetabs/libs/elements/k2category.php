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
// no direct access
defined('_JEXEC') or die ('Restricted access');

class JFormFieldK2category extends JFormField
{
    /**
     * @access private
     */
	var	$_name = 'k2category';

	function getInput()
	{
		$categories = array();
		
		if(is_file(JPATH_SITE.DS.  "components" . DS . "com_k2" . DS . "k2.php"))
		{
			$db 	= &JFactory::getDBO();
			$query 	= 'SELECT m.* FROM #__k2_categories m WHERE published=1 AND trash = 0 ORDER BY parent, ordering';
			$db->setQuery($query);
			$mitems = $db->loadObjectList();
			$children = array();
			if ($mitems)
			{
				foreach ($mitems as $v)
				{
					$pt 	= $v->parent;
					$list 	= @$children[$pt] ? $children[$pt] : array();
					array_push($list, $v);
					$children[$pt] = $list;
				}
			}
			$list 		= JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
			$categories = array();
			$categories[0] = new stdClass();
			$categories[0]->value = '';
			$categories[0]->text = JText::_('-- '.JText::_('All Categories'));
			
			foreach ($list as $item)
			{
				$tmp = new stdClass();
				$tmp->value = $item->id;
				$tmp->text = JText::_('---| '.$item->treename);
				$categories[] = $tmp;
			}
		}
		$output= JHTML::_('select.genericlist',  $categories, ''.$this->name.'[]', 'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $this->value);
		return $output;
	}
}