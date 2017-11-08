<?php
/**
 * @package    Redshop.Cli
 *
 * @copyright  Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

error_reporting(0);
ini_set('display_errors', 0);

// Initialize Joomla framework
require_once dirname(__FILE__) . '/joomla_framework.php';

// Configure error reporting to maximum for CLI output.
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * This script will Generate XML for websosanh
 *
 * @package  Redshop.Cli
 * @since    1.5.0
 */
class Wss_DataFeedApplicationCli extends JApplicationCli
{
	/**
	 * Entry point for the script
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function doExecute()
	{
		JFactory::getApplication('site');
		$params   = $this->getParams();
		$products = $this->getProducts();

		$this->out('Number of products for generate:' . count($products));

		if (empty($products))
		{
			return;
		}

		$data = array();

		foreach ($products as $key => $product)
		{
			// Print out product info
			$this->out('============================');
			$this->out(
				sprintf('Generating "%s" Product "%s" - Category "%s" - SKU "%s":',
					($key + 1),
					$product->product_name,
					$product->category_name,
					$product->product_number
				)
			);

			$productId     = $product->product_id;
			$categoryId    = $product->cat_in_sefurl;
			$stockroom     = RedshopHelperStockroom::getFinalStockofProduct($productId, 0);
			$productPrice  = RedshopHelperProductPrice::formattedPrice($product->product_price);
			$discountPrice = RedshopHelperProductPrice::formattedPrice($product->discount_price);

			$itemData = producthelper::getInstance()->getMenuInformation(
				0,
				0,
				'',
				'product&pid=' . $productId
			);

			$itemId = count($itemData) > 0 ? $itemData->id : RedshopHelperUtility::getItemId($productId, $categoryId);

			$url = $params->get('url') . 'index.php?option=com_redshop&view=product&pid=' . $productId
				. '&cid=' . $categoryId . '&Itemid=' . $itemId;

			$data[$product->product_number] = array(
				'simple_sku'               => $product->product_number,
				'parent_sku'               => '',
				'availability_instock'     => $stockroom,
				'brand'                    => $product->manufacturer_name ? $product->manufacturer_name : '',
				'product_name'             => $product->product_name,
				'description'              => $product->product_desc,
				'currency'                 => Redshop::getConfig()->get('CURRENCY_CODE'),
				'price'                    => $productPrice,
				'discount'                 => $productPrice - $discountPrice,
				'discounted_price'         => $discountPrice,
				'parent_of_parent_of_cat1' => '',
				'parent_of_cat_1'          => '',
				'category_1'               => $product->category_name,
				'parent_of_parent_of_cat2' => '',
				'parent_of_cat_2'          => '',
				'category_2'               => '',
				'parent_of_parent_of_cat3' => '',
				'parent_of_cat3'           => '',
				'category_3'               => '',
				'picture_url'              => $params->get('url') . $product->product_full_image,
				'picture_url2'             => '',
				'picture_url3'             => '',
				'picture_url4'             => '',
				'picture_url5'             => '',
				'URL'                      => $url,
				'promotion'                => '',
				'delivery_period'          => ''
			);
		}

		if ($this->storeXML($data))
		{
			$this->out('============================');
			$this->out('Generate XML Successful !');
		}

		$this->out('============================');
		$this->out('Done !');
	}

	/**
	 * Get payments pending for checking
	 *
	 * @return mixed
	 */
	public function getProducts()
	{
		$params        = $this->getParams();
		$categories    = $params->get('category');
		$manufacturers = $params->get('manufacturer');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('p.*')
			->select($db->qn('m.manufacturer_name'))
			->select($db->qn('c.name', 'category_name'))
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_manufacturer', 'm') . ' ON '
				. $db->qn('p.manufacturer_id') . ' = ' . $db->qn('m.manufacturer_id')
			)
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON '
				. $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id')
			)
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON '
				. $db->qn('pcx.category_id') . ' = ' . $db->qn('c.id')
			)
			->where($db->qn('p.published') . ' = 1');

		if (!empty($manufacturers))
		{
			$query->where($db->qn('p.manufacturer_id') . ' IN (' . implode(',', $manufacturers) . ')');
		}

		if (!empty($categories))
		{
			$query->where($db->qn('c.id') . ' IN (' . implode(',', $categories) . ')');
		}

		$db->setQuery($query);
		$items = $db->loadObjectList();

		return $items;
	}

	/**
	 * Get WSS Datafeed params
	 *
	 * @return JRegistry
	 */
	public function getParams()
	{
		$plugin = JPluginHelper::getPlugin('redshop_product', 'wss_datafeed');

		return new JRegistry($plugin->params);
	}

	/**
	 * Set Product data
	 *
	 * @param   array  $data  Product data
	 *
	 * @return  string
	 */
	public function buildXML($data)
	{
		if (empty($data))
		{
			return '';
		}

		$parentXml = '';

		foreach ($data as $row)
		{
			$row = (array) $row;
			$xml = '';

			foreach ($row as $key => $value)
			{
				if (is_bool($value))
				{
					$value = $value === true ? 'true' : 'false';
				}

				$xml .= $this->tag($key, '<![CDATA[' . $value . ']]>');
			}

			$parentXml .= $this->tag('Product', $xml);
		}

		$parentXml = $this->tag('Products', $parentXml);

		return '<?xml version="1.0" encoding="UTF-8"?>' . $parentXml;
	}

	/**
	 * Build tag
	 *
	 * @param   string  $tagName  Tag name
	 * @param   string  $value    XML data
	 *
	 * @return  string
	 */
	private function tag($tagName = '', $value = '')
	{
		return '<' . $tagName . '>' . $value . '</' . $tagName . '>';
	}

	/**
	 * Store XML
	 *
	 * @param   array  $data  Product Data
	 *
	 * @return  boolean
	 */
	private function storeXML($data)
	{
		$xml = $this->buildXML($data);

		$storePath = JPATH_SITE . '/' . $this->getParams()->get('path');
		$storeFile = $storePath . '/datafeed.xml';

		return JFile::write($storeFile, $xml);
	}
}

JApplicationCli::getInstance('Wss_DataFeedApplicationCli')->execute();
