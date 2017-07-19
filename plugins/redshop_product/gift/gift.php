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
 * Generate product gift
 *
 * @since 1.0.0
 */
class PlgRedshop_ProductGift extends JPlugin
{
	/**
	 * Gift Data
	 *
	 * @var  array
	 *
	 * @since  1.0.0
	 */
	private $giftData = array();

	/**
	 * Constructor
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An optional associative array of configuration settings
	 *
	 * @since   1.0.0
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_product_gift', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * onBeforeDisplayProduct - Replace {product_gift_template}
	 *
	 * @param   string  $templateContent  Template content
	 * @param   object  $params           Params
	 * @param   object  $product          Product detail
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onBeforeDisplayProduct(&$templateContent, $params, $product)
	{
		if ($product->product_type != 'gift')
		{
			return;
		}

		$this->replaceProductGiftData($templateContent, $product);

		return;
	}

	/**
	 * getProductGift - Return Product Gift Data from DB
	 *
	 * @param   int   $productId  Product ID
	 * @param   int   $giftId     Gift ID
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	private function getProductGift($productId, $giftId = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			array(
				'g.*',
				$db->qn('p.product_name'),
				$db->qn('p.product_price'),
				$db->qn('p.product_number')
			)
		)
		->from($db->qn('#__redshop_product_gift', 'g'))
		->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = g.gift_id')
		->where($db->qn('g.product_id') . ' = ' . (int) $productId)
		->order($db->qn('g.quantity_from') . ' ASC');

		if ($giftId > 0)
		{
			$query->where($db->qn('gift_id') . '=' . (int) $giftId);
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get Gift by quantity
	 *
	 * @param   int   $productId  Product ID
	 * @param   int   $quantity   Quantity
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	private function getGiftQuantity($productId, $quantity = 1)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('gift_id'))
			->select($db->qn('quantity'))
			->from($db->qn('#__redshop_product_gift'))
			->where($db->qn('product_id') . ' = ' . (int) $productId)
			->where($db->qn('quantity_from') . ' <= ' . (int) $quantity)
			->where($db->qn('quantity_to') . ' >= ' . (int) $quantity);

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * replaceProductGiftData
	 *
	 * @param   string  $templateContent  Template content
	 * @param   object  $product          Product detail
	 *
	 * @return  void
	 * @since  1.0.0
	 */
	private function replaceProductGiftData(&$templateContent, $product)
	{
		if (strpos($templateContent, '{product_gift_table}') !== false)
		{
			$giftData = $this->getProductGift($product->product_id);

			$table = RedshopLayoutHelper::render(
					'table',
					array('giftData' => $giftData),
					JPATH_PLUGINS . '/redshop_product/gift/layouts'
				);

			$templateContent = str_replace("{product_gift_table}", $table, $templateContent);
		}
	}

	/**
	 * onBeforeSetCartSession - Add product gift data to cart
	 *
	 * @param   array  $cart  Cart data
	 * @param   array  $data  Post data
	 * @param   int    $idx   Cart Index
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onBeforeSetCartSession(&$cart, $data, $idx)
	{
		$giftData = $this->getGiftQuantity($data['product_id'], $data['quantity']);

		if (empty($giftData))
		{
			return;
		}

		$result = array();

		foreach ($giftData as $key => $gift)
		{
			$result[$key]['quantity']   = $gift->quantity;
			$result[$key]['product_id'] = $gift->gift_id;
			$cart[$idx]['gift']         = $result;
			$cart[$idx]['has_gift']     = 1;
		}
	}

	/**
	 * onSameCartProduct - Add product gift data to cart
	 *
	 * @param   array  $cart  Cart data
	 * @param   int    $idx   Cart Index
	 * @param   array  $data  Post data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onAfterCartUpdate(&$cart, $idx, $data)
	{
		$productId       = $cart[$idx]['product_id'];
		$productQuantity = $cart[$idx]['quantity'];
		$giftData = $this->getGiftQuantity($productId, $productQuantity);

		if (empty($giftData))
		{
			return;
		}

		$result = array();

		foreach ($giftData as $key => $gift)
		{
			$result[$key]['quantity']   = $gift->quantity;
			$result[$key]['product_id'] = $gift->gift_id;
			$cart[$idx]['gift']         = $result;
		}

		return;
	}

	/**
	 * onCartItemDisplay - Replace {product_gift} on cart view
	 *
	 * @param   string  $cartMdata  Cart template
	 * @param   array   $cart       Cart array
	 * @param   int     $i          Cart index
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onCartItemDisplay(&$cartMdata, $cart, $i)
	{
		if (!isset($cart[$i]['gift']))
		{
			$cartMdata = str_replace("{product_gift}", "", $cartMdata);

			return;
		}

		$html = RedshopLayoutHelper::render(
			'cart',
			array('data' => $cart[$i]['gift']),
			JPATH_PLUGINS . '/redshop_product/gift/layouts'
		);

		$cartMdata = str_replace("{product_gift}", $html, $cartMdata);

		return;
	}

	/**
	 * onOrderItemDisplay - Replace {product_gift} on order, mail
	 *
	 * @param   string  $cartMdata  Cart template
	 * @param   array   $rowitem    Cart array
	 * @param   int     $i          Cart index
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onOrderItemDisplay(&$cartMdata, &$rowitem, $i)
	{
		$giftRows = $this->getOrderGift($rowitem[$i]->order_item_id);

		if (count($giftRows) <= 0)
		{
			$cartMdata = str_replace("{product_gift}", "", $cartMdata);

			return;
		}

		$data = array();

		foreach ($giftRows as $key => $value)
		{
			$data[$key]['product_id'] = $value->gift_id;
			$data[$key]['quantity']   = $value->quantity;
		}

		$html = RedshopLayoutHelper::render(
			'cart',
			array('data' => $data),
			JPATH_PLUGINS . '/redshop_product/gift/layouts'
		);

		$cartMdata = str_replace("{product_gift}", $html, $cartMdata);

		return;
	}

	/**
	 * afterOrderItemSave - Save product gift data to order_gift table
	 *
	 * @param   array   $cart     Cart data
	 * @param   object  $rowitem  Order Item
	 * @param   int     $i        Cart index
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function afterOrderItemSave($cart, $rowitem, $i)
	{
		if (empty($cart[$i]['gift']))
		{
			return;
		}

		$db = JFactory::getDbo();

		$giftData = $cart[$i]['gift'];

		foreach ($giftData as $key => $gift)
		{
			$columns = array('order_item_id', 'product_id', 'gift_id', 'quantity');

			$values = array(
				(int) $rowitem->order_item_id,
				(int) $rowitem->product_id,
				(int) $gift['product_id'],
				(int) $gift['quantity']
			);

			// Prepare the insert query.
			$query = $db->getQuery(true)
				->insert($db->qn('#__redshop_order_gift'))
				->columns($db->qn($columns))
				->values(implode(',', $values));

			$db->setQuery($query)->execute();
		}
	}

	/**
	 * onDisplayOrderItemNote - Display Prodct gift detail on order detail on backend
	 *
	 * @param   object  $orderItem  Order Item
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onDisplayOrderItemNote($orderItem)
	{
		$giftRows = $this->getOrderGift($orderItem->order_item_id);

		if (count($giftRows) <= 0)
		{
			return;
		}

		$data = array();

		foreach ($giftRows as $key => $value)
		{
			$data[$key]['product_id'] = $value->gift_id;
			$data[$key]['quantity']   = $value->quantity;
		}

		$html = RedshopLayoutHelper::render(
			'cart',
			array('data' => $data),
			JPATH_PLUGINS . '/redshop_product/gift/layouts'
		);

		echo $html;
	}

	/**
	 * Get Order Gift
	 *
	 * @param   int  $orderItemId  Order Item
	 *
	 * @return  object
	 *
	 * @since  1.0.0
	 */
	public function getOrderGift($orderItemId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_order_gift'))
			->where($db->qn('order_item_id') . ' = ' . $db->q((int) $orderItemId));

		return $db->setQuery($query)->loadObjectList();
	}
}
