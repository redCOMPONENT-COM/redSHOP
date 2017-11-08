<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 *  PlgRedshop_ProductProduct_AltText installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.0
 */
class PlgRedshop_ProductProduct_AltText extends JPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * onAfterProductSave
	 *
	 * @param   object   &$product  Data
	 * @param   boolean  &$altText  New or exist
	 *
	 * @return  void
	 */
	public function onChangeMainProductImageAlternateText(&$product, &$altText)
	{
		$altText = $this->params->get('product_image_alt_text', '');

		if (isset($product->product_name))
		{
			$altText = str_replace('{product_name}', $product->product_name, $altText);
		}

		if (isset($product->category_name))
		{
			$altText = str_replace('{category_name}', $product->category_name, $altText);
		}
		elseif (isset($product->category_id))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('name'))
				->from($db->qn('#__redshop_category'))
				->where($db->qn('id') . ' = ' . $product->category_id);

			$categoryName = $db->setQuery($query)->loadResult();

			if (!empty($categoryName))
			{
				$altText = str_replace('{category_name}', $categoryName, $altText);
			}
		}
	}
}
