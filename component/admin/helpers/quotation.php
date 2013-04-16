<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/extra_field.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';

class quotationHelper
{
	public $_data = null;
	public $_table_prefix = null;
	public $_db = null;

	public function __construct()
	{
		$this->_db = JFactory::getDbo();
		$this->_table_prefix = '#__redshop_';
	}

	public function getQuotationStatusList()
	{
		$status = array();
		$status[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$status[] = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_REQUESTED'));
		$status[] = JHTML::_('select.option', 2, JText::_('COM_REDSHOP_REPLIED'));
		$status[] = JHTML::_('select.option', 3, JText::_('COM_REDSHOP_ACCEPTED'));
		$status[] = JHTML::_('select.option', 4, JText::_('COM_REDSHOP_REJECTED'));
		$status[] = JHTML::_('select.option', 5, JText::_('COM_REDSHOP_ORDERED'));

		return $status;
	}

	public function getQuotationStatusName($value = 0)
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

	public function getQuotationProduct($quotation_id = 0, $quotation_item_id = 0)
	{
		$and = "";

		if ($quotation_id != 0)
		{
			$and .= "AND quotation_id IN (" . $quotation_id . ") ";
		}

		if ($quotation_item_id != 0)
		{
			$and .= "AND quotation_item_id='" . $quotation_item_id . "' ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "quotation_item "
			. "WHERE 1=1 "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getQuotationDetail($quotation_id)
	{
		$query = "SELECT q.*,q.user_email AS quotation_email,u.* FROM " . $this->_table_prefix . "quotation AS q "
			. "LEFT JOIN " . $this->_table_prefix . "users_info AS u ON u.user_id=q.user_id AND u.address_type Like 'BT' "
			. "WHERE q.quotation_id='" . $quotation_id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function generateQuotationNumber()
	{
		/* Generated a unique quotation number */
		$query = "SELECT COUNT(quotation_id) FROM " . $this->_table_prefix . "quotation ";
		$this->_db->setQuery($query);
		$maxId = $this->_db->loadResult();

		$number = $maxId + 1;

		return $number;
	}

	public function updateQuotationStatus($quotation_id, $status = 1)
	{
		$query = "UPDATE " . $this->_table_prefix . "quotation "
			. "SET quotation_status='" . $status . "' "
			. "WHERE quotation_id='" . $quotation_id . "' ";
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	public function getQuotationUserList()
	{
		$user = JFactory::getUser();
		$and = "";

		if ($user->id)
		{
			$and = " AND q.user_id='" . $user->id . "' ";
		}

		$query = "SELECT q.* FROM " . $this->_table_prefix . "quotation AS q "
			. "WHERE 1=1 "
			. $and
			. "ORDER BY quotation_cdate DESC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function randomQuotationEncrkey($p_length = '30')
	{
		/* Generated a unique order number */
		$char_list = "abcdefghijklmnopqrstuvwxyz";
		$char_list .= "1234567890123456789012345678901234567890123456789012345678901234567890";

		$random = "";
		srand(( double ) microtime() * 1000000);

		for ($i = 0; $i < $p_length; $i++)
		{
			$random .= substr($char_list, (rand() % (strlen($char_list))), 1);
		}

		return $random;
	}

	public function manageQuotationUserfield($cart = array(), $quotation_item_id = 0, $section_id = 12)
	{
		$extra_field = new extra_field;
		$row_data = $extra_field->getSectionFieldList($section_id, 1);

		for ($i = 0; $i < count($row_data); $i++)
		{
			if (array_key_exists($row_data[$i]->field_name, $cart) && $cart[$row_data[$i]->field_name])
			{
				$user_fields = $cart[$row_data[$i]->field_name];

				if ($user_fields != '')
				{
					$this->insertQuotationUserfield($row_data[$i]->field_id, $quotation_item_id, $section_id, $user_fields);
				}
			}
		}
		return true;
	}

	public function insertQuotationUserfield($field_id = 0, $quotation_item_id = 0, $section_id = 12, $value = '')
	{
		$sql = "INSERT INTO " . $this->_table_prefix . "quotation_fields_data "
			. "(fieldid,data_txt,quotation_item_id,section) "
			. "VALUE ('" . $field_id . "','" . $value . "','" . $quotation_item_id . "','" . $section_id . "')";
		$this->_db->setQuery($sql);
		$this->_db->query();
	}

	public function getQuotationUserfield($quotation_item_id)
	{
		$q = "SELECT qf.*,f.* FROM " . $this->_table_prefix . "quotation_fields_data AS qf "
			. "LEFT JOIN " . $this->_table_prefix . "fields AS f ON f.field_id=qf.fieldid "
			. "WHERE quotation_item_id='" . $quotation_item_id . "'";
		$this->_db->setQuery($q);
		$row_data = $this->_db->loadObjectlist();

		return $row_data;
	}

	public function displayQuotationUserfield($quotation_item_id = 0, $section_id = 12)
	{
		$redTemplate = new Redtemplate;
		$producthelper = new producthelper;
		$resultArr = array();

		$sql = "SELECT fd.*,f.field_title,f.field_type,f.field_name "
			. "FROM " . $this->_table_prefix . "quotation_fields_data AS fd "
			. "LEFT JOIN " . $this->_table_prefix . "fields AS f ON f.field_id=fd.fieldid "
			. "WHERE fd.quotation_item_id=" . $quotation_item_id . " AND fd.section = " . $section_id;
		$this->_db->setQuery($sql);
		$userfield = $this->_db->loadObjectlist();

		if (count($userfield) > 0)
		{
			$quotationItem = $this->getQuotationProduct(0, $quotation_item_id);
			$product_id = $quotationItem[0]->product_id;

			$productdetail = $producthelper->getProductById($product_id);
			$productTemplate = $redTemplate->getTemplate("product", $productdetail->product_template);

			$returnArr = $producthelper->getProductUserfieldFromTemplate($productTemplate[0]->template_desc);
			$userFieldTag = $returnArr[1];

			for ($i = 0; $i < count($userFieldTag); $i++)
			{
				for ($j = 0; $j < count($userfield); $j++)
				{
					if ($userfield[$j]->field_name == $userFieldTag[$i])
					{
						if ($userfield[$j]->field_type == 10)
						{
							$files = explode(",", $userfield[$j]->data_txt);
							$data_txt = "";
							for ($f = 0; $f < count($files); $f++)
							{
								$u_link = REDSHOP_FRONT_DOCUMENT_ABSPATH . "product/" . $files[$f];
								$data_txt .= "<a href='" . $u_link . "'>" . $files[$f] . "</a> ";
							}
							$resultArr[] = $userfield[$j]->field_title . " : " . $data_txt;
						}
						else
						{
							$resultArr[] = $userfield[$j]->field_title . " : " . $userfield[$j]->data_txt;
						}
					}
				}
			}
		}
		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr .= "<br/>" . implode("<br/>", $resultArr);
		}

		return $resultstr;
	}

	public function updateQuotationwithOrder($quotation_id, $order_id)
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'quotation '
			. 'SET order_id="' . $order_id . '" '
			. 'WHERE quotation_id=' . $quotation_id;
		$this->_db->setQuery($query);
		$this->_db->query();
		$this->updateQuotationStatus($quotation_id, 5);

		return true;
	}

	public function getQuotationwithOrder($order_id = 0)
	{
		$and = "";

		if ($order_id != 0)
		{
			$and = " AND q.order_id IN (" . $order_id . ") ";
		}

		$query = "SELECT q.* FROM " . $this->_table_prefix . "quotation AS q "
			. "WHERE 1=1 "
			. $and
			. "ORDER BY quotation_cdate DESC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getQuotationItemAccessoryDetail($quotation_item_id = 0)
	{
		$and = "";

		if ($quotation_item_id != 0)
		{
			$and .= " AND quotation_item_id='" . $quotation_item_id . "' ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "quotation_accessory_item "
			. "WHERE 1=1 "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getQuotationItemAttributeDetail($quotation_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		$and = "";

		if ($quotation_item_id != 0)
		{
			$and .= " AND quotation_item_id='" . $quotation_item_id . "' ";
		}

		if ($parent_section_id != 0)
		{
			$and .= " AND parent_section_id='" . $parent_section_id . "' ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "quotation_attribute_item "
			. "WHERE is_accessory_att='" . $is_accessory . "' "
			. "AND section='" . $section . "' "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}
}
