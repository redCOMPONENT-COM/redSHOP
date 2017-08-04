<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');

/**
 * Generate Product gift
 *
 * @since  1.0.0
 */
class PlgRedshop_Product_TypeGift extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 * @since   1.0.0
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_product_type_gift', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Get Product gift Product type
	 *
	 * @return  array Gift Product type
	 *
	 * @since   1.0.0
	 */
	public function onListProductTypes()
	{
		return array('value' => 'gift', 'text' => JText::_('PLG_REDSHOP_PRODUCT_TYPE_GIFT_TYPE_NAME'));
	}

	/**
	 * onDisplayProductTabs
	 *
	 * @param   object  $product  Product detail
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onDisplayProductTypeData($product)
	{
		$db     = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			array(
				'g.*',
				$db->qn('p.product_name'),
				$db->qn('p.product_price')
			)
		)
		->from($db->qn('#__redshop_product_gift', 'g'))
		->innerJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = g.gift_id')
		->where($db->qn('g.product_id') . '=' . (int) $product->product_id);

		$db->setQuery($query);
		$giftData = $db->loadObjectList();

		echo RedshopLayoutHelper::render(
			'gift',
			array
			(
				'product'  => $product,
				'giftData' => $giftData
			),
			JPATH_PLUGINS . '/redshop_product_type/gift/layouts'
		);
	}

	/**
	 * onAfterProductSave - Save Gift product
	 *
	 * @param   object  $product  Product detail
	 * @param   bool    $isNew    Is new?
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAfterProductSave(&$product, $isNew)
	{
		$post = JFactory::getApplication()->input->post->getArray();

		if (!isset($post['product_gift']) || count($post['product_gift']) <= 0)
		{
			return;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Delete all product gift
		$query->delete($db->qn('#__redshop_product_gift'));
		$query->where($db->qn('product_id') . ' = ' . (int) $product->product_id);

		$db->setQuery($query);
		$db->execute();

		$giftProducts = $post['product_gift'];

		foreach ($giftProducts as $giftProduct)
		{
			$query = $db->getQuery(true);

			// Insert columns.
			$columns = array('id', 'product_id', 'gift_id', 'quantity', 'quantity_from', 'quantity_to');

			// Insert values.
			$values = array(
				(int) $giftProduct['id'],
				(int) $giftProduct['product_id'],
				(int) $giftProduct['gift_id'],
				(int) $giftProduct['quantity'],
				(int) $giftProduct['quantity_from'],
				(int) $giftProduct['quantity_to']
			);

			// Prepare the insert query.
			$query
				->insert($db->qn('#__redshop_product_gift'))
				->columns($db->qn($columns))
				->values(implode(',', $values));

			$db->setQuery($query);

			$db->execute();
		}
	}
}
