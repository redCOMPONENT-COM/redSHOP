<?php
/**
 * $ModDesc
 *
 * @version        $Id: helper.php $Revision
 * @package        modules
 * @subpackage     $Subpackage
 * @copyright      Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website    htt://landofcoder.com
 * @license        GNU General Public License version 2
 */
// no direct access
defined('_JEXEC') or die;

JLoader::import('redshop.library');

class JFormFieldRedshopcategory extends JFormField
{

	/**
	 * @access private
	 */
	var $_name = 'redshopcategory';

	protected function getInput()
	{
		$name         = $this->name;
		$categories = RedshopHelperCategory::getCategoryListArray();
		array_unshift($categories, JHTML::_('select.option', '0', JText::_('MOD_REDSHOP_CATEGORIES_SELECT_CATEGORY'), 'category_id', 'category_name'));
		ob_start();
		$output = JHTML::_('select.genericlist', $categories, $name, 'class="inputbox"', 'category_id', 'category_name', $this->value, $name);
		ob_end_clean();

		return $output;
	}
}
