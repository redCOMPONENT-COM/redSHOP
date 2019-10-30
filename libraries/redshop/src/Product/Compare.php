<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product;

defined('_JEXEC') or die;

/**
 * Compare helper
 *
 * @since  2.1.0
 */
class Compare
{
	/**
	 * Method for generate compare product
	 *
	 * @return  string  HTML layout of compare div
	 *
	 * @since   2.1.0
	 *
	 * @throws  \Exception
	 */
	public static function generateCompareProduct()
	{
		$input           = \JFactory::getApplication()->input;
		$cmd             = $input->get('cmd');
		$compareProducts = \JFactory::getSession()->get('compare_product');

		if (empty($compareProducts))
		{
			return '';
		}

		return \RedshopLayoutHelper::render(
			'shop.compare_product',
			array(
				'compareProducts' => $compareProducts,
				'excludeData'     => empty($cmd),
				'itemId'          => $input->getInt('Itemid', 0)
			)
		);
	}

	/**
	 * Method for get category compare product template
	 *
	 * @param   integer $cid Category ID
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public static function getCategoryCompareTemplate($cid = 0)
	{
		if (!$cid)
		{
			return \Redshop::getConfig()->getInt('COMPARE_TEMPLATE_ID');
		}

		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('t.id'))
			->from($db->qn('#__redshop_template', 't'))
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('t.id') . ' = ' . $db->qn('c.compare_template_id'))
			->where($db->qn('c.id') . ' = ' . (int) $cid)
			->where($db->qn('t.published') . ' = 1');

		$result = $db->setQuery($query)->loadResult();

		return !$result ? \Redshop::getConfig()->getInt('COMPARE_TEMPLATE_ID') : (int) $result;
	}

	/**
	 * @param   integer $productId        Product ID
	 * @param   integer $categoryId       Category ID
	 * @param   string  $html             Template HTML
	 * @param   integer $isRelatedProduct Is related product.
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public static function replaceCompareProductsButton($productId = 0, $categoryId = 0, $html = "", $isRelatedProduct = 0)
	{
		$prefix = $isRelatedProduct == 1 ? "related" : "";

		if (empty(\Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE')))
		{
			$html = str_replace("{" . $prefix . "compare_product_div}", "", $html);
			$html = str_replace("{" . $prefix . "compare_products_button}", "", $html);

			return $html;
		}

		// For compare product div...
		if (strpos($html, '{' . $prefix . 'compare_product_div}') !== false)
		{
			$compareProductHtml = \RedshopLayoutHelper::render('product.compare');

			$html = str_replace("{compare_product_div}", $compareProductHtml, $html);
		}

		if (strpos($html, '{' . $prefix . 'compare_products_button}') !== false)
		{
			if ($categoryId == 0)
			{
				$categoryId = \productHelper::getInstance()->getCategoryProduct($productId);
			}

			$compareButton        = new \stdClass;
			$compareButton->text  = \JText::_("COM_REDSHOP_ADD_TO_COMPARE");
			$compareButton->value = $productId . '.' . $categoryId;

			$compareProduct = \JHtml::_(
				'redshopselect.checklist',
				array($compareButton),
				'rsProductCompareChk',
				array('cssClassSuffix' => ' no-group'),
				'value',
				'text',
				(new \RedshopProductCompare)->getItemKey($productId)
			);

			$html = str_replace("{" . $prefix . "compare_products_button}", $compareProduct, $html);
		}

		return $html;
	}
}
