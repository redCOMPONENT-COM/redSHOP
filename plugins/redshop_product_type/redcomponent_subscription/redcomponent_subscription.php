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
 * redCOMPONENT subscription
 *
 * @since  1.0.0
 */
class PlgRedshop_Product_TypeRedcomponent_Subscription extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Get Product types
	 *
	 * @return  array
	 */
	public function onListProductTypes()
	{
		return array('value' => 'redcomponent_subscription', 'text' => JText::_('PLG_REDSHOP_PRODUCT_TYPE_REDCOMPONENT_SUBSCRIPTION_NAME'));
	}

	/**
	 * onDisplayProductTypeData
	 *
	 * @param   object  $product  Product detail
	 *
	 * @return  void
	 */
	public function onDisplayProductTypeData($product)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->qn('#__redshop_redcomponent_subscription'))
			->where($db->qn('product_id') . '=' . (int) $product->product_id);

		$db->setQuery($query);
		$subscriptionDetail = $db->loadObject();

		$subscriptions = array();
		$period        = '';

		if ($subscriptionDetail)
		{
			$query = $db->getQuery(true);
			$query->select(
				array(
					$db->qn('p.product_id'),
					$db->qn('p.product_name'),
					$db->qn('p.product_price')
				)
			)
				->from($db->qn('#__redshop_product', 'p'))
				->where($db->qn('p.product_id') . ' IN (' . $subscriptionDetail->subscriptions . ')');

			$db->setQuery($query);

			$subscriptions = $db->loadObjectList();

			$period = $subscriptionDetail->period;
		}

		echo RedshopLayoutHelper::render(
			'redcomponent_subscription',
			array
			(
				'product'       => $product,
				'subscriptions' => $subscriptions,
				'period'        => $period
			),
			JPATH_PLUGINS . '/redshop_product_type/redcomponent_subscription/layouts'
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

		if (!isset($post['productSubscription']) || count($post['productSubscription']) <= 0)
		{
			return;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->delete($db->qn('#__redshop_redcomponent_subscription'))
			->where($db->qn('product_id') . ' = ' . (int) $product->product_id);

		$db->setQuery($query);
		$db->execute();

		$subscriptions = implode(',', $post['productSubscription']);


		$query = $db->getQuery(true);

		// Insert columns.
		$columns = array('product_id', 'subscriptions', 'period');

		// Insert values.
		$values = array(
			(int) $product->product_id,
			$db->q($subscriptions),
			(int) $post['productSubscriptionPeriod'],
		);

		// Prepare the insert query.
		$query
			->insert($db->qn('#__redshop_redcomponent_subscription'))
			->columns($db->qn($columns))
			->values(implode(',', $values));

		$db->setQuery($query);

		$db->execute();
	}
}
