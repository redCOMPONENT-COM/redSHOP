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
 * @since  2.0.4
 */
class PlgRedshop_ProductBundle extends JPlugin
{
	/**
	 *  Bundle Data
	 *
	 * @var  array
	 *
	 * @since  2.0.4
	 */
	private $bundleData = array();

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
     * @since   2.0.4
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_product_bundle', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * onBeforeDisplayProduct - Replace {bundle_template}
	 *
	 * @param   string  &$templateContent  Template content
	 * @param   object  $params            Params
	 * @param   object  $product           Product detail
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	public function onBeforeDisplayProduct(&$templateContent, $params, $product)
	{
		if ($product->product_type == 'bundle')
		{
			$document = JFactory::getDocument();
			$document->addScriptDeclaration('
				function selectBundle(product_name, product_id, bundle_id, property_id)
				{
					document.getElementById("bundle_product_" + product_id + "_"  + bundle_id).value = property_id;
					document.getElementById("bundle_title_" + product_id + "_"  + bundle_id).innerHTML = product_name;
					getExtraParamsArray["bundle_product[" + bundle_id + "]"] = property_id;

				}
			');

			$this->replaceBundleData($templateContent, $product);
		}
	}

	/**
	 * getBundleData - Return Bundle Data from database
	 *
	 * @param   int   $productId  Product ID
	 * @param   int   $bundleId   Bundle ID
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	private function getBundleData($productId, $bundleId = 0)
	{
		$key = md5($productId . '_' . $bundleId);

		if (!array_key_exists($key, $this->bundleData))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select(
				array(
					'b.*',
					$db->qn('p.product_name'),
					$db->qn('p.product_price'),
					$db->qn('p.product_number')
				)
			)
			->select($db->qn('p.product_name'))
			->from($db->qn('#__redshop_product_bundle', 'b'))
			->innerJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = b.bundle_id')
			->where($db->qn('b.product_id') . '=' . (int) $productId)
			->order($db->qn('b.ordering') . ' ASC');

			if ($bundleId > 0)
			{
				$query->where($db->qn('bundle_id') . '=' . (int) $bundleId);
			}

			$db->setQuery($query);
			$this->bundleData[$key] = $db->loadObjectList();
		}

		return $this->bundleData[$key];
	}

	/**
	 * replaceBundleData
	 *
	 * @param   string  &$templateContent  Template content
	 * @param   object  $product           Product detail
	 *
	 * @return  void
	 * @since  2.0.4
	 */
	private function replaceBundleData(&$templateContent, $product)
	{
		$bundleTemplates = RedshopHelperTemplate::getTemplate('bundle_template');
		$bundleTemplate = $bundleTemplates[0];

		$this->bundleData = $this->getBundleData($product->product_id);

		if (count($this->bundleData) <= 0)
		{
			$templateContent = str_replace("{bundle_template:$bundleTemplate->template_name}", "", $templateContent);

			return $templateContent;
		}

		$bundleContent = "";

		foreach ($this->bundleData as $bundleDetail)
		{
			$productDetail = RedshopHelperProduct::getProductById($bundleDetail->bundle_id);

			$bundleContent .= RedshopLayoutHelper::render(
				'bundle',
				array
				(
					'detail' => $bundleDetail,
					'content' => $this->replaceAttributeData($productDetail, $bundleDetail, $bundleTemplate)
				),
				JPATH_PLUGINS . '/redshop_product/bundle/layouts'
			);
		}

		$templateContent = str_replace("{bundle_template:$bundleTemplate->template_name}", $bundleContent, $templateContent);
	}

	/**
	 * replaceAttributeData - Replace Property detail
	 *
	 * @param   object  $productDetail   Product detail
	 * @param   object  $bundleDetail    Bundle detail
	 * @param   object  $bundleTemplate  Bundle Template
	 *
	 * @return  object
	 *
	 * @since  2.0.4
	 */
	private function replaceAttributeData($productDetail, $bundleDetail, $bundleTemplate)
	{
		$attributes = $productDetail->attributes;
		$attributeTable = "";

		if (count($attributes) > 0)
		{
			foreach ($attributes as $attribute)
			{
				$attributeTable .= $bundleTemplate->template_desc;

				$attributeTable = str_replace("{property_image_lbl}", JText::_('COM_REDSHOP_PROPERTY_IMAGE_LBL'), $attributeTable);
				$attributeTable = str_replace("{property_number_lbl}", JText::_('COM_REDSHOP_VIRTUAL_NUMBER_LBL'), $attributeTable);
				$attributeTable = str_replace("{property_name_lbl}", JText::_('COM_REDSHOP_PROPERTY_NAME_LBL'), $attributeTable);
				$attributeTable = str_replace("{property_price_lbl}", JText::_('COM_REDSHOP_PROPERTY_PRICE_LBL'), $attributeTable);
				$attributeTable = str_replace("{property_stock_lbl}", JText::_('COM_REDSHOP_PROPERTY_STOCK_LBL'), $attributeTable);

				if (empty($attribute->properties))
				{
					$properties = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute->attribute_id);
				}
				else
				{
					$properties = $attribute->properties;
				}

				if (empty($attribute->text) || empty($properties)
					|| strpos($attributeTable, "{property_start}") === false || strpos($attributeTable, "{property_start}") === false)
				{
					continue;
				}

				$start            = explode("{property_start}", $attributeTable);
				$end              = explode("{property_end}", $start[1]);
				$propertyTemplate = $end[0];

				$propertyData = "";

				foreach ($properties as $property)
				{
					$propertyData .= $propertyTemplate;

					$priceWithVat    = 0;
					$priceWithoutVat = 0;
					$propertyStock         = RedshopHelperStockroom::getStockAmountWithReserve($property->value, "property");
					$preOrderPropertyStock = RedshopHelperStockroom::getPreorderStockAmountwithReserve($property->value, "property");

					$propertyData = str_replace("{property_name}", urldecode($property->property_name), $propertyData);
					$propertyData = str_replace("{property_number}", $property->property_number, $propertyData);

					// Replace {property_stock}
					if (strpos($propertyData, '{property_stock}') !== false)
					{
						$displayStock = ($propertyStock) ? JText::_('COM_REDSHOP_IN_STOCK') : JText::_('COM_REDSHOP_NOT_IN_STOCK');
						$propertyData = str_replace("{property_stock}", $displayStock, $propertyData);
					}

					// Replace {property_image}
					if (strpos($propertyData, '{property_image}') !== false)
					{
						$propertyImage = "";

						if ($property->property_image
							&& is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property->property_image))
						{
							$thumbUrl = RedshopHelperMedia::getImagePath(
								$property->property_image,
								'',
								'thumb',
								'product_attributes',
								$mpw_thumb,
								$mph_thumb,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
							$property_image = "<img title='" . urldecode($property->property_name) . "' src='" . $thumbUrl . "'>";
						}

						$propertyData = str_replace("{property_image}", $propertyImage, $propertyData);
					}

					// Replease {property_select}
					if (strpos($propertyData, '{property_select}') !== false)
					{
						$propertySelect = '<a class="bundleselect" onclick="selectBundle(\'' . $property->property_name . '\',' . $bundleDetail->product_id . ',' . $bundleDetail->bundle_id . ',' . $property->property_id . ')">' . JText::_('JLIB_FORM_BUTTON_SELECT') . '</span></a>';

						$propertyData = str_replace("{property_select}", $propertySelect, $propertyData);
					}

					JPluginHelper::importPlugin('redshop_product');
					$dispatcher = RedshopHelperUtility::getDispatcher();
					$dispatcher->trigger('onPropertyAddtoCart', array(&$propertyData, &$cart_template, &$propertyStock, $property->property_id, $productDetail));
				}

				$attributeTable = str_replace("{property_start}", "", $attributeTable);
				$attributeTable = str_replace("{property_end}", "", $attributeTable);
				$attributeTable = str_replace($propertyTemplate, $propertyData, $attributeTable);
			}
		}

		return $attributeTable;
	}

	/**
	 * onAddtoCart - Add some params for cart form.
	 *
	 * @param   string  &$cartform  Cart Form
	 * @param   object  $product    Product detail
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	public function onAddtoCart(&$cartform, $product)
	{
		$this->bundleData = $this->getBundleData($product->product_id);

		if ($product->product_type == 'bundle' && count($this->bundleData) > 0)
		{
			foreach ($this->bundleData as $bundleDetail)
			{
				$bundleId = 'bundle_product_' . $product->product_id . '_' . $bundleDetail->bundle_id;
				$bundleName = 'bundle_product[' . $bundleDetail->bundle_id . ']';

				$cartform .= '<input type="hidden" id="' . $bundleId . '" name="' . $bundleName . '">';
			}
		}
	}

	/**
	 * onBeforeSetCartSession - Add bundle_product data to cart
	 *
	 * @param   array  &$cart  Cart data
	 * @param   array  $data   Post data
	 * @param   int  $idx      Cart Index
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	public function onBeforeSetCartSession(&$cart, $data, $idx)
	{
		$cart[$idx]['bundle_product'] = $data['bundle_product'];
	}

	/**
	 * onCartItemDisplay - Replace {bundle_product} on cart view
	 *
	 * @param   string  &$cartMdata  Cart template
	 * @param   array  $cart        Cart array
	 * @param   int  $i           Cart index
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	public function onCartItemDisplay(&$cartMdata, $cart, $i)
	{
		$bundleContent = "";

		if (isset($cart[$i]['bundle_product']))
		{
			$data = array();

			$bundleProduct = $cart[$i]['bundle_product'];

			$this->bundleData = $this->getBundleData($cart[$i]['product_id']);

			if (count($bundleProduct) > 0 && count($this->bundleData) > 0)
			{
				foreach ($this->bundleData as $bundleData)
				{
					$propertyData = array();

					if (!empty($bundleProduct[$bundleData->bundle_id]))
					{
						$properties = RedshopHelperProduct_Attribute::getAttributeProperties($bundleProduct[$bundleData->bundle_id]);
						$propertyData = $properties[0];
					}

					$data[] = array
					(
						$propertyData,
						$bundleData
					);
				}

				$bundleContent = RedshopLayoutHelper::render(
					'cart',
					array
					(
						'data' => $data
					),
					JPATH_PLUGINS . '/redshop_product/bundle/layouts'
				);
			}
		}

		$cartMdata = str_replace("{bundle_product}", $bundleContent, $cartMdata);

		return $cartMdata;
	}

	/**
	 * onOrderItemDisplay - Replace {bundle_product} on order, mail
	 *
	 * @param   string  &$cartMdata  Cart template
	 * @param   array  $cart        Cart array
	 * @param   int  $i           Cart index
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	public function onOrderItemDisplay(&$cartMdata, &$rowitem, $i)
	{
		$bundleContent = "";

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
		->from($db->qn('#__redshop_order_bundle'))
		->where($db->qn('order_item_id') . '=' . (int) $rowitem[$i]->order_item_id);

		$db->setQuery($query);
		$bundleRows = $db->loadObjectList();

		if (count($bundleRows) > 0)
		{
			foreach ($bundleRows as $row)
			{
				$propertyData = array();

				if ($row->property_id > 0)
				{
					$properties = RedshopHelperProduct_Attribute::getAttributeProperties($row->property_id);
					$propertyData = $properties[0];
				}

				$bundleData = $this->getBundleData($row->product_id, $row->bundle_id);

				$data[] = array
				(
					$propertyData,
					$bundleData[0]
				);
			}

			$bundleContent = RedshopLayoutHelper::render(
				'cart',
				array
				(
					'data' => $data
				),
				JPATH_PLUGINS . '/redshop_product/bundle/layouts'
			);
		}

		$cartMdata = str_replace("{bundle_product}", $bundleContent, $cartMdata);

		return $cartMdata;
	}

	/**
	 * afterOrderItemSave - Save bundle data to order_bundle table
	 *
	 * @param   array   $cart     Cart data
	 * @param   object  $rowitem  Order Item
	 * @param   int     $i        Cart index
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	public function afterOrderItemSave($cart, $rowitem, $i)
	{
		if (count($cart[$i]['bundle_product']) > 0)
		{
			$db = JFactory::getDbo();

			$bundleData = $cart[$i]['bundle_product'];

			foreach ($bundleData as $bundleId => $propertyId)
			{
				$query = $db->getQuery(true);

				// Insert columns.
				$columns = array('order_item_id', 'product_id', 'bundle_id', 'property_id');

				// Insert values.
				$values = array(
					(int) $rowitem->order_item_id,
					(int) $rowitem->product_id,
					(int) $bundleId,
					(int) $propertyId
				);

				// Prepare the insert query.
				$query
					->insert($db->qn('#__redshop_order_bundle'))
					->columns($db->qn($columns))
					->values(implode(',', $values));

				$db->setQuery($query);

				$db->execute();
			}
		}
	}

	/**
	 * onDisplayOrderItemNote - Display Bundle detail on order detail on backend
	 *
	 * @param   object  $orderItem  Order Item
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	public function onDisplayOrderItemNote($orderItem)
	{
		$bundleContent = "";

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
		->from($db->qn('#__redshop_order_bundle'))
		->where($db->qn('order_item_id') . '=' . (int) $orderItem->order_item_id);

		$db->setQuery($query);
		$bundleRows = $db->loadObjectList();

		if (count($bundleRows) > 0)
		{
			foreach ($bundleRows as $row)
			{
				$propertyData = array();

				if ($row->property_id > 0)
				{
					$properties = RedshopHelperProduct_Attribute::getAttributeProperties($row->property_id);
					$propertyData = $properties[0];
				}

				$bundleData = $this->getBundleData($row->product_id, $row->bundle_id);

				$data[] = array
				(
					$propertyData,
					$bundleData[0]
				);
			}

			$bundleContent = RedshopLayoutHelper::render(
				'cart',
				array
				(
					'data' => $data
				),
				JPATH_PLUGINS . '/redshop_product/bundle/layouts'
			);
		}

		echo $bundleContent;
	}

	/**
	 * checkSameCartProduct - If add 2 products with same bundle data
	 *
	 * @param   array  &$cart          Cart data
	 * @param   array  $data           Post data
	 * @param   bool   &$sameProduct   Same
	 * @param   int    $i              Cart index
	 *
	 * @return  void
	 *
	 * @since  2.0.4
	 */
	public function checkSameCartProduct(&$cart, $data, &$sameProduct, $i)
	{
		$sel = $data['bundle_product'];
		$preSelect = $cart[$i]['bundle_product'];

		$newDiff1 = array_diff($sel, $preSelect);
		$newDiff2 = array_diff($preSelect, $sel);

		if (count($newDiff1) > 0 || count($newDiff2) > 0)
		{
			$sameProduct = false;
		}
	}
}

