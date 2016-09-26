<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Class PlgRedshop_ProductStockroom_Status
 *
 * @since  1.5
 */
class PlgRedshop_ProductStockroom_Status extends JPlugin
{
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_product_stockroom_status', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param   int  $order_id  Order id
	 *
	 * @return  void
	 */
	public function getStockroomStatus($order_id)
	{
		if (Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') == "")
		{
			return;
		}

		$mailData = $this->params->get(
			'template',
			RedshopLayoutHelper::render('sample', null, JPATH_PLUGINS . '/redshop_product/stockroom_status/layouts')
		);

		if (!(strstr($mailData, "{product_loop_start}") && strstr($mailData, "{product_loop_end}")))
		{
			return;
		}

		$templateSdata = explode('{product_loop_start}', $mailData);
		$templateStart = $templateSdata[0];
		$templateEdata = explode('{product_loop_end}', $templateSdata[1]);
		$templateEnd = $templateEdata[1];
		$templateMiddle = $templateEdata[0];
		$middleData = '';

		$order_functions = order_functions::getInstance();
		$stock_flag = 0;
		$orderproducts = $order_functions->getOrderItemDetail($order_id);

		for ($p = 0, $cp = count($orderproducts); $p < $cp; $p++)
		{
			$orderItemId = $orderproducts[$p]->order_item_id;

			if ($attArr = $order_functions->getOrderItemAttributeDetail($orderItemId, 0, "attribute", $orderproducts[$p]->product_id))
			{
				foreach ($attArr as $att)
				{
					if ($propArr = $order_functions->getOrderItemAttributeDetail($orderItemId, 0, "property", $att->section_id))
					{
						foreach ($propArr as $prop)
						{
							if ($subPropArr = $order_functions->getOrderItemAttributeDetail($orderItemId, 0, "subproperty", $prop->section_id))
							{
								foreach ($subPropArr as $subProp)
								{
									$middleData .= $templateMiddle;
									$this->checkStockRoomAmount($subProp->stockroom_id, $subProp->section_id, 'subproperty', $stock_flag, $middleData);
								}
							}
							else
							{
								$middleData .= $templateMiddle;
								$this->checkStockRoomAmount($prop->stockroom_id, $prop->section_id, 'property', $stock_flag, $middleData);
							}
						}
					}
				}
			}
			else
			{
				$middleData .= $templateMiddle;
				$this->checkStockRoomAmount($orderproducts[$p]->stockroom_id, $orderproducts[$p]->product_id, 'product', $stock_flag, $middleData);
			}
		}

		$message = $templateStart . $middleData . $templateEnd;

		if ($stock_flag == 1)
		{
			$mailbcc = null;
			$app = JFactory::getApplication();
			$mailFrom = $app->get('mailfrom');
			$fromName = $app->get('fromname');
			$replyTo = trim($this->params->get('replyTo', ''));
			$redshopMail = redshopMail::getInstance();
			$message = $redshopMail->imginmail($message);

			if ($replyTo != '')
			{
				$mailbcc = explode(",", $replyTo);
			}

			JFactory::getMailer()->sendMail(
				$mailFrom, $fromName, Redshop::getConfig()->get('ADMINISTRATOR_EMAIL'), $this->params->get('mailSubject', 'Stockroom Status Mail'), $message, 1, null, $mailbcc
			);
		}
	}

	/**
	 * Check Stockroom Amount
	 *
	 * @param   string  $stockrooms  List stockrooms
	 * @param   int     $sectionId   Id section
	 * @param   string  $section     Name section
	 * @param   int     &$stockFlag  Flag stock
	 * @param   string  &$message    Template mail message
	 *
	 * @return  void
	 */
	private function checkStockRoomAmount($stockrooms, $sectionId, $section, &$stockFlag, &$message)
	{
		if ($stockrooms = explode(",", $stockrooms))
		{
			$stockRoomHelper = rsstockroomhelper::getInstance();
			$db = JFactory::getDbo();

			foreach ($stockrooms as $stockroom)
			{
				if ($stockDetails = $stockRoomHelper->getStockroomDetail($stockroom))
				{
					$minStockAmount = $stockDetails[0]->min_stock_amount;
					$stockStatus = $stockRoomHelper->getStockAmountwithReserve($sectionId, $section, $stockroom);

					if ($stockStatus <= $minStockAmount)
					{
						switch ($section)
						{
							case 'subproperty':
								$query = $db->getQuery(true)
									->select('psp.*, p.product_name, p.product_number, pap.property_name, pa.attribute_name')
									->from($db->qn('#__redshop_product_subattribute_color', 'psp'))
									->leftJoin($db->qn('#__redshop_product_attribute_property', 'pap') . ' ON pap.property_id = psp.subattribute_id')
									->leftJoin($db->qn('#__redshop_product_attribute', 'pa') . ' ON pa.attribute_id = pap.attribute_id')
									->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = pa.product_id')
									->where('psp.subattribute_color_id = ' . (int) $sectionId);

								if ($sectionDetail = $db->setQuery($query)->loadObject())
								{
									$sectionDetail->product_name = JText::sprintf(
										'PLG_REDSHOP_PRODUCT_STOCKROOM_STATUS_SUB_PROPERTY_TEMPLATE',
										$sectionDetail->product_name, $sectionDetail->attribute_name, $sectionDetail->property_name,
										$sectionDetail->subattribute_color_title, $sectionDetail->subattribute_color_name
									);
								}
								break;
							case 'property':
								$query = $db->getQuery(true)
									->select('pap.*, p.product_name, p.product_number, pa.attribute_name')
									->from($db->qn('#__redshop_product_attribute_property', 'pap'))
									->leftJoin($db->qn('#__redshop_product_attribute', 'pa') . ' ON pa.attribute_id = pap.attribute_id')
									->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = pa.product_id')
									->where('pap.property_id = ' . (int) $sectionId);

								if ($sectionDetail = $db->setQuery($query)->loadObject())
								{
									$sectionDetail->product_name = JText::sprintf(
										'PLG_REDSHOP_PRODUCT_STOCKROOM_STATUS_PROPERTY_TEMPLATE',
										$sectionDetail->product_name, $sectionDetail->attribute_name, $sectionDetail->property_name
									);
								}
								break;
							case 'product':
							default:
								$sectionDetail = RedshopHelperProduct::getProductById($sectionId);
							break;
						}

						if ($sectionDetail)
						{
							$stockFlag = 1;
							$message = str_replace("{product_number}", $sectionDetail->product_number, $message);
							$message = str_replace("{product_name}", $sectionDetail->product_name, $message);
							$message = str_replace("{stockroom_name}", $stockDetails[0]->stockroom_name, $message);
							$message = str_replace("{stock_status}", $stockStatus, $message);
						}
					}
				}
			}
		}

		return;
	}
}
