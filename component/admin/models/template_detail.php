<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class RedshopModelTemplate_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_copydata = null;

	public $names = array();

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JFactory::getApplication()->input->get('cid', 0, 'array');

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
		$red_template = Redtemplate::getInstance();

		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'template WHERE template_id = ' . $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			// Read template from file and replace it with database template description
			if (isset($this->_data->template_section))
			{
				$this->_data->template_name = strtolower($this->_data->template_name);
				$this->_data->template_name = str_replace(" ", "_", $this->_data->template_name);
				$template_desc = $this->_data->template_desc;

				$this->_data->template_desc = $red_template->readtemplateFile($this->_data->template_section, $this->_data->template_name, true);

				if ($this->_data->template_desc == "")
				{
					$this->_data->template_desc = $template_desc;
				}
			}

			return (boolean) $this->_data;
		}

		return true;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->template_id = 0;
			$detail->template_name = null;
			$detail->template_desc = null;
			$detail->template_section = null;
			$detail->published = 1;
			$detail->payment_methods = null;
			$detail->shipping_methods = null;
			$detail->order_status = null;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$red_template = Redtemplate::getInstance();

		$row = $this->getTable();

		if (isset($data['payment_methods']) && count($data['payment_methods']) > 0)
		{
			$data['payment_methods'] = implode(',', $data['payment_methods']);
		}

		if (isset($data['shipping_methods']) && count($data['shipping_methods']) > 0)
		{
			$data['shipping_methods'] = implode(',', $data['shipping_methods']);
		}

		if (isset($data['order_status']) && count($data['order_status']) > 0)
		{
			$data['order_status'] = implode(',', $data['order_status']);
		}

		$data['template_name'] = strtolower($data['template_name']);
		$data['template_name'] = str_replace(" ", "_", $data['template_name']);

		$tempate_file = $red_template->getTemplatefilepath($data['template_section'], $data['template_name'], true);

		JFile::write($tempate_file, $data["template_desc"]);

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$row->shipping_methods = $row->shipping_methods ? $row->shipping_methods : '';
		$row->payment_methods = $row->payment_methods ? $row->payment_methods : '';
		$row->order_status = $row->order_status ? $row->order_status : '';

		if ($row->template_id)
		{
			$this->_id = $row->template_id;
			$this->_loadData();

			if ($row->template_name != $this->_data->template_name)
			{
				$tempate_file = $red_template->getTemplatefilepath($this->_data->template_section, $this->_data->template_name, true);
				JFile::delete($tempate_file);
			}
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function delete($cid = array())
	{
		$red_template = Redtemplate::getInstance();

		if (count($cid))
		{
			for ($i = 0, $in = count($cid); $i < $in; $i++)
			{
				$query = 'SELECT * FROM ' . $this->_table_prefix . 'template WHERE template_id = ' . $cid[$i];
				$this->_db->setQuery($query);
				$rs = $this->_db->loadObject();

				$tempate_file = $red_template->getTemplatefilepath($rs->template_section, $rs->template_name, true);

				JFile::delete($tempate_file);
			}

			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'template WHERE template_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE ' . $this->_table_prefix . 'template'
				. ' SET published = ' . intval($publish)
				. ' WHERE template_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function copy($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'SELECT * FROM ' . $this->_table_prefix . 'template WHERE template_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);
			$this->_copydata = $this->_db->loadObjectList();
		}

		if(is_null($this->_copydata))
		{
			return false;
		}

		foreach ($this->_copydata as $cdata)
		{
			$post['template_id'] = 0;
			$post['template_name'] = $this->renameToUniqueValue('template_name', $cdata->template_name, 'dash');
			$post['template_section'] = $cdata->template_section;
			$post['template_desc'] = $cdata->template_desc;
			$post['order_status'] = $cdata->order_status;
			$post['payment_methods'] = $cdata->payment_methods;
			$post['published'] = $cdata->published;
			$post['shipping_methods'] = $cdata->shipping_methods;

			$this->store($post);
			$this->names[] = array($cdata->template_name,$post['template_name']);
		}

		return true;
	}

	public function availabletexts($section)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'textlibrary WHERE published=1 AND section like "' . $section . '"';
		$this->_db->setQuery($query);
		$this->textdata = $this->_db->loadObjectList();

		return $this->textdata;
	}

	public function availableaddtocart($section)
	{
		$query = 'SELECT template_name FROM ' . $this->_table_prefix . 'template WHERE published=1 AND template_section = "' . $section . '"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	/**
	 * Method to checkout/lock the template_detail
	 *
	 * @access    public
	 *
	 * @param    int $uid    User ID of the user checking the helloworl detail out
	 *
	 * @return    boolean    True on success
	 * @since    1.5
	 */
	public function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid))
			{
				$user = JFactory::getUser();
				$uid = (int) $user->get('id');
			}

			// Lets get to it and checkout the thing...
			$template_detail = $this->getTable('template_detail');

			if (!$template_detail->checkout($uid, $this->_id))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * Method to checkin/unlock the template_detail
	 *
	 * @access    public
	 * @return    boolean    True on success
	 * @since    1.5
	 */
	public function checkin()
	{
		if ($this->_id)
		{
			$template_detail = $this->getTable('template_detail');

			if (!$template_detail->checkin($this->_id))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return false;
	}

	/**
	 * Tests if template_detail is checked out
	 *
	 * @access    public
	 *
	 * @param    int    A user id
	 *
	 * @return    boolean    True if checked out
	 * @since    1.5
	 */
	public function isCheckedOut($uid = 0)
	{
		if ($this->_loadData())
		{
			if ($uid)
			{
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			}
			else
			{
				return $this->_data->checked_out;
			}
		}
	}
}
