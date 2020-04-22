<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


class RedshopModelRating_detail extends RedshopModelForm
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JFactory::getApplication()->input->get('cid', 0, 'array');

		$this->setId((int) $array[0]);
	}

    /**
     * @param $id
     */
	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

    /**
     * @return |null
     */
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

    /**
     * @return bool
     */
	public function _loadData()
	{
		$query = $this->_db->getQuery(true);

		if (empty($this->_data))
		{
			$query->select(array('p.*', 'IFNULL(u.name, p.username) AS username', 'pr.product_name'))
				->from(array($this->_db->qn('#__redshop_product', 'pr'), $this->_db->qn('#__redshop_product_rating', 'p')))
				->join('LEFT', $this->_db->qn('#__users', 'u') . ' ON (' . $this->_db->qn('u.id') . ' = ' . $this->_db->qn('p.userid') . ')')
				->where($this->_db->qn('p.rating_id') . ' = ' . $this->_db->q($this->_id))
				->where($this->_db->qn('p.product_id') . ' = ' . $this->_db->qn('pr.product_id'));

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail              = new stdClass;
			$detail->rating_id   = null;
			$detail->product_id  = null;
			$detail->title       = null;
			$detail->comment     = null;
			$detail->userid      = null;
			$detail->time        = null;
			$detail->user_rating = null;
			$detail->favoured    = null;
			$detail->published   = 1;
			$this->_data         = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

    /**
     * @param $data
     * @return bool|JTable
     * @throws Exception
     * @since 3.0
     */
	public function store($data)
	{
		// Set email for existing joomla user
		if (isset($data['userid']) && $data['userid'] > 0)
		{
			$user             = \JFactory::getUser($data['userid']);
			$data['email']    = $user->email;
			$data['username'] = $user->username;
		}

		$row = $this->getTable();

		// Check if this rate is rated before
		$rtn = $row->load([
		    'userid' => $data['userid'],
            'product_id' => $data['product_id']
        ]);

		// This one is not rated before
		if ($rtn === false)
		{
			if (!$row->bind($data))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			if (!$row->store())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
		else
		{
			return false;
		}

		$row               = $data;
		$row['cid']         = 0;
		$row['rating_id']  = $data['cid'] ? $data['cid'] : $this->_db->insertid();
		$row['comment']   = $data['comment'];
		$row['cdate']      = time();
		$row['user_rating']   = $data['user_rating'];

		return $row;
	}

    /**
     * @param array $pks
     * @return bool
     * @throws Exception
     * @since 3.0
     */
	public function delete(&$pks)
	{
		return \Redshop\Rating\Helper::removeRatings($pks);
	}

    /**
     * @param array $pks
     * @param int $value
     * @return bool
     * @since 3.0
     */
	public function publish(&$pks, $value = 1)
	{
        return \Redshop\Rating\Helper::setPublish($pks, $value);
	}

    /**
     * @return mixed
     * @since 3.0
     */
	public function getUsers()
	{
	    return \Redshop\User\Helper::getUsers(
	        [
	            'u.id' => 'value',
                'u.name' => 'text'
            ]
        );
	}

    /**
     * @return mixed|null
     * @throws Exception
     */
	public function getProducts()
	{
		$productId = \JFactory::getApplication()->input->get('pid');

		if ($productId)
		{
            return \Redshop\Product\Product::getProductById((int) $productId);
		}

		return null;
	}

    /**
     * @param $uid
     * @return string
     * @since 3.0
     */
    public function getUseFullName($uid)
    {
        return \Redshop\User\Helper::getUserFullName($uid);
    }
}
