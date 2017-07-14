<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class GiftcardModelGiftcard
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelGiftcard extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	/**
	 * Product data
	 */
	public $_product = null;

	public $_table_prefix = null;

	public $_template = null;

	public $_limit = null;

	public function __construct()
	{
		$app = JFactory::getApplication();
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$Id                  = JRequest::getInt('gid', 0);

		$this->setId((int) $Id);
	}

	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	public function _buildQuery()
	{
		$app = JFactory::getApplication();

		$and = "";

		if ($this->_id)
		{
			$and .= "AND giftcard_id = " . (int) $this->_id . " ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "giftcard "
			. "WHERE published = 1 "
			. $and;

		return $query;
	}

	public function getData()
	{
		if (empty ($this->_data))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query);
		}

		return $this->_data;
	}

	public function getGiftcardTemplate()
	{
		$redTemplate = Redtemplate::getInstance();

		if (!$this->_id)
		{
			$carttemplate = $redTemplate->getTemplate("giftcard_list");
		}
		else
		{
			$carttemplate = $redTemplate->getTemplate("giftcard");
		}

		return $carttemplate;
	}
}
