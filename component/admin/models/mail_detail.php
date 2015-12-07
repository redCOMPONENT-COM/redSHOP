<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperAdminThumbnail');
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

class RedshopModelMail_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', 0, '', 'array');

		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		if ($this->_loadData())
		{
		}

		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		$db = JFactory::getDbo();

		if (empty($this->_data))
		{
			$query = 'SELECT * FROM #__redshop_mail WHERE mail_id = ' . $this->_id;
			$db->setQuery($query);
			$this->_data = $db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}


	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->mail_id = 0;
			$detail->mail_name = null;
			$detail->mail_subject = null;
			$detail->mail_section = 0;
			$detail->mail_order_status = null;
			$detail->mail_body = null;
			$detail->published = 1;
			$detail->mail_bcc = null;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM #__redshop_mail WHERE mail_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE #__redshop_mail'
				. ' SET published = ' . intval($publish)
				. ' WHERE mail_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function mail_section()
	{
		$db = JFactory::getDbo();

		$query = 'SELECT order_status_code as value, concat(order_status_name," (",order_status_code,")") as text FROM '
			. '#__redshop_order_status  where published=1';

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function order_statusHtml($order_status)
	{
		$select = array();

		$select[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_Select'));

		$merge = array_merge($select, $order_status);

		return JHTML::_('select.genericlist', $merge, 'mail_order_status', 'class="inputbox" size="1" title="" ', 'value', 'text');
	}
}
