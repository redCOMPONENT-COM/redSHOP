<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Class JFormFieldRedshopCategoryRemove
 *
 * @since  1.5
 */
class JFormFieldRedshopCategoryRemove extends JFormField
{
	/**
	 * @access private
	 */
	protected $name = 'redshopcategoryremove';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		if (!is_dir(JPATH_ADMINISTRATOR . '/components/com_redshop'))
		{
			return JText::_('COM_REDSHOP_REDSHOP_IS_NOT_INSTALLED');
		}

		$values  = array();

		if (is_array($this->value))
		{
			foreach ($this->value as $_k => $tmpV)
			{
				$values[$tmpV] = $tmpV;
			}
		}

		$product_category = new product_category;
		ob_start();
		$output = $product_category->list_all('' . $this->name . '[]', '', $values, 10, false, true);
		ob_end_clean();

		return $output;
	}
}
