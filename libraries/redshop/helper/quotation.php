<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Class Redshop Helper for Quotation
 *
 * @since  2.0.3
 */
class RedshopHelperQuotation
{
	/**
	 * Get list of quotation status
	 *
	 * @return  array  An array of status options
	 *
	 * @since  2.0.3
	 */
	public static function getQuotationStatusList()
	{
		$status   = array();
		$status[] = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$status[] = JHtml::_('select.option', 1, JText::_('COM_REDSHOP_REQUESTED'));
		$status[] = JHtml::_('select.option', 2, JText::_('COM_REDSHOP_REPLIED'));
		$status[] = JHtml::_('select.option', 3, JText::_('COM_REDSHOP_ACCEPTED'));
		$status[] = JHtml::_('select.option', 4, JText::_('COM_REDSHOP_REJECTED'));
		$status[] = JHtml::_('select.option', 5, JText::_('COM_REDSHOP_ORDERED'));

		return $status;
	}

	/**
	 * Get name of quotation status
	 *
	 * @param   integer $value Have 5 options: REQUESTED/REPLIED/ACCEPTED/REJECTED/ORDERED
	 *
	 * @return  string   Name of Quotation status
	 *
	 * @since  2.0.3
	 */
	public static function getQuotationStatusName($value = 0)
	{
		$name = "-";

		switch ($value)
		{
			case 1:
				$name = JText::_('COM_REDSHOP_REQUESTED');
				break;
			case 2:
				$name = JText::_('COM_REDSHOP_REPLIED');
				break;
			case 3:
				$name = JText::_('COM_REDSHOP_ACCEPTED');
				break;
			case 4:
				$name = JText::_('COM_REDSHOP_REJECTED');
				break;
			case 5:
				$name = JText::_('COM_REDSHOP_ORDERED');
				break;
		}

		return $name;
	}

	/**
	 * Get quotation product
	 *
	 * @param   integer $quotationId     Quotation ID
	 * @param   integer $quotationItemId Quotation Item ID
	 *
	 * @return  array
	 *
	 * @since  2.0.3
	 */
	public static function getQuotationProduct($quotationId = 0, $quotationItemId = 0)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('q.*')
			->from($db->qn('#__redshop_quotation_item', 'q'))
			->where('1 = 1');

		if ($quotationId != 0)
		{
			// Sanitize ids
			$quotationId = explode(',', $quotationId);
			$quotationId = ArrayHelper::toInteger($quotationId);

			$query->where($db->qn('q.quotation_id') . " IN (" . implode(',', $quotationId) . ")");
		}

		if ($quotationItemId != 0)
		{
			$query->where($db->qn('q.quotation_item_id') . " = " . (int) $quotationItemId);
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get quotation detail by quotation ID
	 *
	 * @param   integer $quotationId Quotation ID
	 *
	 * @return  object
	 *
	 * @since  2.0.3
	 */
	public static function getQuotationDetail($quotationId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('q.*')
			->select($db->qn('q.user_email', 'quotation_email'))
			->select('u.*')
			->from($db->qn('#__redshop_quotation', 'q'))
			->leftJoin(
				$db->qn('#__redshop_users_info', 'u') . ' ON ' . $db->qn('u.user_id') . ' = ' . $db->qn('q.user_id')
				. ' AND ' . $db->qn('u.address_type') . ' LIKE ' . $db->quote('BT')
			)
			->where($db->qn('q.quotation_id') . ' = ' . (int) $quotationId);

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Generate a unique quotation number
	 *
	 * @return  integer
	 *
	 * @since   2.0.3
	 */
	public static function generateQuotationNumber()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('quotation_id') . ')')
			->from($db->qn('#__redshop_quotation', 'q'));

		$db->setQuery($query);

		$maxId = $db->loadResult();

		return $maxId + 1;
	}

	/**
	 * Update Quotation Status
	 *
	 * @param   integer $quotationId Quotation ID
	 * @param   integer $status      Quotation Change status
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function updateQuotationStatus($quotationId, $status = 1)
	{
		if (!$quotationId)
		{
			return;
		}

		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base update statement.
		$query->update($db->qn('#__redshop_quotation', 'q'))
			->set($db->qn('q.quotation_status') . ' = ' . (int) $status)
			->where($db->qn('q.quotation_id') . ' = ' . (int) $quotationId);

		// Set the query and execute the update.
		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}
	}

	/**
	 * Get list of quotation users
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getQuotationUserList()
	{
		$user  = JFactory::getUser();
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('q.*')
			->from($db->qn('#__redshop_quotation', 'q'))
			->where('1=1');

		if ($user->id)
		{
			$query->where($db->qn('q.user_id') . ' = ' . (int) $user->id);
		}

		$query->order($db->qn('q.quotation_cdate') . ' DESC');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Generate a random encrytion key for quotation
	 *
	 * @param   string $pLength Length of string to generate
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function randomQuotationEncryptKey($pLength = '30')
	{
		/* Generated a unique order number */
		$charList = "abcdefghijklmnopqrstuvwxyz";
		$charList .= "1234567890123456789012345678901234567890123456789012345678901234567890";

		$random = "";
		srand((double) microtime() * 1000000);

		for ($i = 0; $i < $pLength; $i++)
		{
			$random .= substr($charList, (rand() % (strlen($charList))), 1);
		}

		return $random;
	}

	/**
	 * Inserting quotation user's fields
	 *
	 * @param   array   $cart            Array of fields to insert
	 * @param   integer $quotationItemId Item ID of Quotation to match
	 * @param   integer $sectionId       Section to get field list
	 *
	 * @return  boolean  true/false when inserting success or fail
	 *
	 * @since   2.0.3
	 */
	public static function manageQuotationUserField($cart = array(), $quotationItemId = 0, $sectionId = 12)
	{
		$rowData = RedshopHelperExtrafields::getSectionFieldList($sectionId, 1);

		if (empty($rowData))
		{
			return false;
		}

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			if (array_key_exists($rowData[$i]->name, $cart) && $cart[$rowData[$i]->name])
			{
				$userFields = $cart[$rowData[$i]->name];

				if ($userFields != '')
				{
					self::insertQuotationUserField($rowData[$i]->id, $quotationItemId, $sectionId, $userFields);
				}
			}
		}

		return true;
	}

	/**
	 * Insert quotation field with value
	 *
	 * @param   integer $fieldId         Field ID
	 * @param   integer $quotationItemId Quotation Item ID
	 * @param   integer $sectionId       Section ID
	 * @param   string  $value           Value to insert
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function insertQuotationUserField($fieldId = 0, $quotationItemId = 0, $sectionId = 12, $value = '')
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->insert($db->qn('#__redshop_quotation_fields_data', 'qfd'))
			->columns($db->qn(array('qfd.fieldid', 'qfd.data_txt', 'qfd.quotation_item_id', 'qfd.section')))
			->values(implode(',', array((int) $fieldId, $db->quote($value), (int) $quotationItemId, (int) $db->quote($sectionId))));

		$db->setQuery($query)->execute();
	}

	/**
	 * Get quotation item fields by field ID
	 *
	 * @param   integer $quotationItemId Quotation Item ID
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getQuotationUserField($quotationItemId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('qf.*')
			->select('f.*')
			->from($db->qn('#__redshop_quotation_fields_data', 'qf'))
			->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('f.id') . ' = ' . $db->qn('qf.fieldid'))
			->where($db->qn('qf.quotation_item_id') . ' = ' . (int) $quotationItemId);

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Display quotation user fields
	 *
	 * @param   integer $quotationItemId Quotation Item ID
	 * @param   integer $sectionId       Section ID
	 *
	 * @return  string   HTML to display
	 *
	 * @since   2.0.3
	 */
	public static function displayQuotationUserField($quotationItemId = 0, $sectionId = 12)
	{
		/**
		 * @TODO: ProductHelper will be deprecated,
		 * replace them with approriated classes
		 */
		$productHelper = productHelper::getInstance();

		$resultArr = array();
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true);

		$query->select('fd.*')
			->select($db->qn(array('f.title', 'f.type', 'f.name')))
			->from($db->qn('#__redshop_quotation_fields_data', 'fd'))
			->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('f.id') . ' = ' . $db->qn('fd.fieldid'))
			->where($db->qn('fd.quotation_item_id') . ' = ' . $db->q((int) $quotationItemId))
			->where($db->qn('fd.section') . ' = ' . $db->q((int) $sectionId));

		$db->setQuery($query);
		$userField = $db->loadObjectList();

		if (count($userField) > 0)
		{
			$quotationItem = self::getQuotationProduct(0, $quotationItemId);
			$productId     = $quotationItem[0]->product_id;

			$productDetail   = Redshop::product((int) $productId);
			$productTemplate = RedshopHelperTemplate::getTemplate("product", $productDetail->product_template);

			$returnArr    = $productHelper->getProductUserfieldFromTemplate($productTemplate[0]->template_desc);
			$userFieldTag = $returnArr[1];

			for ($i = 0, $in = count($userFieldTag); $i < $in; $i++)
			{
				for ($j = 0, $jn = count($userField); $j < $jn; $j++)
				{
					if ($userField[$j]->name == $userFieldTag[$i])
					{
						if ($userField[$j]->type == 10)
						{
							$files   = explode(",", $userField[$j]->data_txt);
							$dataTxt = "";

							for ($f = 0, $fn = count($files); $f < $fn; $f++)
							{
								$uLink   = REDSHOP_FRONT_DOCUMENT_ABSPATH . "product/" . $files[$f];
								$dataTxt .= "<a href='" . $uLink . "'>" . $files[$f] . "</a> ";
							}

							$resultArr[] = $userField[$j]->title . " : " . $dataTxt;
						}
						else
						{
							$resultArr[] = $userField[$j]->title . " : " . $userField[$j]->data_txt;
						}
					}
				}
			}
		}

		if (empty($resultArr))
		{
			return '';
		}

		return '<br/>' . implode('<br/>', $resultArr);
	}

	/**
	 * Update quotation with order ID
	 *
	 * @param   integer $quotationId Quotation ID
	 * @param   integer $orderId     Order ID
	 *
	 * @return  boolean/void           Return true if success, alert error if fail
	 *
	 * @since   2.0.3
	 */
	public static function updateQuotationWithOrder($quotationId, $orderId)
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base update statement.
		$query->update($db->qn('#__redshop_quotation', 'q'))
			->set($db->qn('q.order_id') . ' = ' . (int) $orderId)
			->where($db->qn('q.quotation_id') . ' = ' . (int) $quotationId);

		// Set the query and execute the update.
		$db->setQuery($query);

		try
		{
			$db->execute();
			self::updateQuotationStatus($quotationId, 5);
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return true;
	}

	/**
	 * Get quotation by order id
	 *
	 * @param   integer /array  $orderId  OrderID
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getQuotationWithOrder($orderId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('q.*')
			->from($db->qn('#__redshop_quotation', 'q'))
			->where('1=1');

		if ($orderId != 0)
		{
			// Sanitize ids
			$orderId = explode(',', $orderId);
			$orderId = ArrayHelper::toInteger($orderId);

			if (is_array($orderId))
			{
				$orderId = implode(',', $orderId);
			}

			$query->where($db->qn('q.order_id') . ' IN (' . $orderId . ')');
		}

		$query->order($db->qn('q.quotation_cdate') . ' DESC');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get quotation accesory item by ID
	 *
	 * @param   integer $quotationItemId Quotation Item ID
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getQuotationItemAccessoryDetail($quotationItemId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('qa.*')
			->from($db->qn('#__redshop_quotation_accessory_item', 'qa'))
			->where('1 = 1');

		if ($quotationItemId != 0)
		{
			$query->where($db->qn('qa.quotation_item_id') . ' = ' . (int) $quotationItemId);
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get Quotation Item Attribute Detail
	 *
	 * @param   integer $quotationItemId Quotation Item ID
	 * @param   integer $isAccessory     Check Accessory Attribute
	 * @param   string  $section         Section: default is "attribute"
	 * @param   integer $parentSectionId Parent section ID
	 *
	 * @return  object
	 *
	 * @since 2.0.3
	 */
	public static function getQuotationItemAttributeDetail($quotationItemId = 0, $isAccessory = 0, $section = "attribute", $parentSectionId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('qat.*')
			->from($db->qn('#__redshop_quotation_attribute_item', 'qat'))
			->where($db->qn('qat.is_accessory_att') . ' = ' . (int) $isAccessory)
			->where($db->qn('qat.section') . ' = ' . $db->quote($section));

		if ($quotationItemId != 0)
		{
			$query->where($db->qn('qat.quotation_item_id') . ' = ' . (int) $quotationItemId);
		}

		if ($parentSectionId != 0)
		{
			$query->where($db->qn('qat.parent_section_id') . ' = ' . (int) $parentSectionId);
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
