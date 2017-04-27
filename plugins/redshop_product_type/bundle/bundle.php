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
 * Generate Bundle product
 *
 * @since  1.0.0
 */
class PlgRedshop_Product_TypeBundle extends JPlugin
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
		$lang->load('plg_redshop_product_type_bundle', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Get Bundle Product type
	 *
	 * @return  array Bundle Product type
     *
     * @since   1.0.0
	 */
	public function onListProductTypes()
	{
		return array('value' => 'bundle', 'text' => JText::_('PLG_REDSHOP_PRODUCT_TYPE_BUNDLE_TYPE_NAME'));
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
				'b.*',
				$db->qn('p.product_name'),
				$db->qn('p.product_price')
			)
		)
		->from($db->qn('#__redshop_product_bundle', 'b'))
		->innerJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = b.bundle_id')
		->where($db->qn('b.product_id') . '=' . (int) $product->product_id);

		$db->setQuery($query);
		$bundleData = $db->loadObjectList();

		echo RedshopLayoutHelper::render(
			'bundle',
			array
			(
				'product'    => $product,
				'bundleData' => $bundleData
			),
			JPATH_PLUGINS . '/redshop_product_type/bundle/layouts'
		);
	}

	/**
	 * onAfterProductSave - Save bundle product
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

		if (!isset($post['product_bundle']) || count($post['product_bundle']) <= 0)
		{
			return;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// delete all product bundle
		$query->delete($db->qn('#__redshop_product_bundle'));
		$query->where($db->qn('product_id') . ' = ' . (int) $product->product_id);

		$db->setQuery($query);
		$db->execute();

		$bundleProducts = $post['product_bundle'];

		foreach ($bundleProducts as $bundleProduct)
		{
			$query = $db->getQuery(true);

			// Insert columns.
			$columns = array('product_id', 'bundle_id', 'bundle_name', 'ordering');

			// Insert values.
			$values = array(
				(int) $bundleProduct['product_id'],
				(int) $bundleProduct['bundle_id'],
				$db->q($bundleProduct['bundle_name']),
				(int) $bundleProduct['ordering']
			);

			// Prepare the insert query.
			$query
				->insert($db->qn('#__redshop_product_bundle'))
				->columns($db->qn($columns))
				->values(implode(',', $values));

			$db->setQuery($query);

			$db->execute();
		}
	}
}
