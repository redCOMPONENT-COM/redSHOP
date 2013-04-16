<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Redaccesslevel
{
	/**
	 * define default path
	 *
	 */
	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
	}

	public function checkaccessofuser($group_id)
	{
		$app = JFactory::getApplication();

		$option = JRequest::getVar('option');
		$db = JFactory::getDBO();
		$query = "SELECT  section_name FROM " . $this->_table_prefix . "accessmanager"
			. " WHERE `view`=1 and `gid` = '" . $group_id . "'";
		$db->setQuery($query);
		$access_section = $db->loadResultArray();

		return $access_section;
	}

	public function checkgroup_access($view, $task, $group_id)
	{
		if ($task == '')
		{
			$this->getgroup_access($view, $group_id);
		}

		else
		{
			if ($task == 'add')
			{
				$this->getgroup_accesstaskadd($view, $task, $group_id);
			}
			elseif ($task == 'edit')
			{
				$this->getgroup_accesstaskedit($view, $task, $group_id);
			}
			elseif ($task == 'remove')
			{
				$this->getgroup_accesstaskdelete($view, $task, $group_id);
			}
		}
	}

	public function getgroup_access($view, $group_id)
	{
		$app = JFactory::getApplication();

		$option = JRequest::getVar('option');
		$db = JFactory::getDBO();

		if ($view == "shipping_rate_detail" || $view == "shipping_rate" || $view == "shipping_detail")
		{
			$view = "shipping";
		}

		elseif ($view == "tax_group_detail" || $view == "tax_detail" || $view == "tax")
		{
			$view = "tax_group";
		}

		elseif ($view == "prices_detail" || $view == "prices" || $view == "mass_discount" || $view == "mass_discount_detail")
		{
			$view = "product";
		}

		elseif ($view == "addorder_detail")
		{
			$view = "order";
		}

		elseif ($view == "user_detail")
		{
			$view = "user";
		}

		elseif ($view == "export")
		{
			$view = "import";
		}

		elseif ($view == "voucher_detail")
		{
			$view = "voucher";
		}

		elseif ($view == "coupon_detail")
		{
			$view = "coupon";
		}

		$query = "SELECT view  FROM " . $this->_table_prefix . "accessmanager"
			. " WHERE `section_name` = '" . $view . "' AND `gid` = '" . $group_id . "'";

		$db->setQuery($query);
		$accessview = $db->loadResult();

		if ($accessview != 1)
		{
			$msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
			$app->redirect($_SERVER['HTTP_REFERER'], $msg);
		}
	}

	public function getgroup_accesstaskadd($view, $task, $group_id)
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();

		if ($view == "shipping_rate_detail" || $view == "shipping_rate" || $view == "shipping_detail")
		{
			$view = "shipping";
		}

		elseif ($view == "tax_group_detail" || $view == "tax_detail" || $view == "tax")
		{
			$view = "tax_group";
		}

		elseif ($view == "prices_detail" || $view == "prices" || $view == "mass_discount" || $view == "mass_discount_detail")
		{
			$view = "product";
		}

		elseif ($view == "addorder_detail")
		{
			$view = "order";
		}

		elseif ($view == "user_detail")
		{
			$view = "user";
		}

		elseif ($view == "export")
		{
			$view = "import";
		}

		elseif ($view == "voucher_detail")
		{
			$view = "voucher";
		}

		elseif ($view == "coupon_detail")
		{
			$view = "coupon";
		}

		$query = "SELECT *  FROM  " . $this->_table_prefix . "accessmanager"
			. " WHERE `section_name` = '" . str_replace('_detail', '', $view) . "' AND `gid` = '" . $group_id . "'";
		$db->setQuery($query);
		$accessview = $db->loadObjectList();

		if ($accessview[0]->add != 1)
		{
			$msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
			$app->redirect($_SERVER['HTTP_REFERER'], $msg);
		}
	}

	public function getgroup_accesstaskedit($view, $task, $group_id)
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();

		if ($view == "shipping_rate_detail" || $view == "shipping_rate" || $view == "shipping_detail")
		{
			$view = "shipping";
		}

		elseif ($view == "tax_group_detail" || $view == "tax_detail" || $view == "tax")
		{
			$view = "tax_group";
		}
		elseif ($view == "prices_detail" || $view == "prices" || $view == "mass_discount" || $view == "mass_discount_detail")
		{
			$view = "product";
		}
		elseif ($view == "addorder_detail")
		{
			$view = "order";
		}
		elseif ($view == "user_detail")
		{
			$view = "user";
		}
		elseif ($view == "export")
		{
			$view = "import";
		}
		elseif ($view == "voucher_detail")
		{
			$view = "voucher";
		}
		elseif ($view == "coupon_detail")
		{
			$view = "coupon";
		}

		// Tax_group_detail
		$query = "SELECT *  FROM  " . $this->_table_prefix . "accessmanager"
			. " WHERE `section_name` = '" . str_replace('_detail', '', $view) . "' AND `gid` = '" . $group_id . "'";

		$db->setQuery($query);
		$accessview = $db->loadObjectList();

		if ($accessview[0]->edit != 1)
		{
			$msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
			$app->redirect($_SERVER['HTTP_REFERER'], $msg);
		}
	}

	public function getgroup_accesstaskdelete($view, $task, $group_id)
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();

		if ($view == "shipping_rate_detail" || $view == "shipping_rate" || $view == "shipping_detail")
		{
			$view = "shipping";
		}
		elseif ($view == "tax_group_detail" || $view == "tax_detail" || $view == "tax")
		{
			$view = "tax_group";
		}
		elseif ($view == "prices_detail" || $view == "prices" || $view == "mass_discount" || $view == "mass_discount_detail")
		{
			$view = "product";
		}
		elseif ($view == "addorder_detail")
		{
			$view = "order";
		}
		elseif ($view == "user_detail")
		{
			$view = "user";
		}
		elseif ($view == "export")
		{
			$view = "import";
		}
		elseif ($view == "voucher_detail")
		{
			$view = "voucher";
		}
		elseif ($view == "coupon_detail")
		{
			$view = "coupon";
		}

		$query = "SELECT *  FROM  " . $this->_table_prefix . "accessmanager"
			. " WHERE `section_name` = '" . str_replace('_detail', '', $view) . "' AND `gid` = '" . $group_id . "'";
		$db->setQuery($query);
		$accessview = $db->loadObjectList();

		if ($accessview[0]->delete != 1)
		{
			$msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
			$app->redirect($_SERVER['HTTP_REFERER'], $msg);
		}
	}
}
