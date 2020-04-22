<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelProducttags_detail extends RedshopModel
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

	public function setId($id)
	{
		$this->_id   = $id;
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

    /**
     * @return bool
     * @since 3.0
     */
	public function _loadData()
	{
		if (empty($this->_data))
		{
		    $db = \JFactory::getDbo();
		    $query = $db->getQuery(true);
		    $query->select('*')
                ->from($db->qn('#__redshop_product_tags'))
                ->where($db->qn('tags_id') . ' = ' . $db->q((int) $this->_id));
			$db->setQuery($query);

			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;

			$detail->tags_id      = 0;
			$detail->tags_name    = null;
			$detail->tags_counter = 0;
			$detail->published    = 1;
			$this->_data          = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row = $this->getTable('product_tags');

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

		return true;
	}

    /**
     * @param array $cid
     * @return bool
     * @throws Exception
     * @since 3.0
     */
	public function delete($cid = [])
	{
		if (is_array($cid) && count($cid))
		{
			$tagIds = implode(',', $cid);
			$query = $this->_db->getQuery(true);
			$db = $this->_db;

			try {
			    $db->transactionStart();

			    # Remove xref between product & tags frist
			    $query->delete($db->qn('#__redshop_product_tags_xref'))
                    ->where($db->qn('tags_id') . ' IN(' . $db->q($tagIds) . ')');
			    $db->setQuery($query);
			    $db->execute();

			    # Remove product_tag.
			    $query->clear();
                $query->delete($db->qn('#__redshop_product_tags'))
                    ->where($db->qn('tags_id') . ' IN (' . $db->q($tagIds) . ')');
                $db->setQuery($query);
                $db->execute();

            } catch (\RuntimeException $e) {
			    $db->transactionRollback();
                \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

                return false;
            }

            return true;
		}

		return false;
	}

    /**
     * @param array $cid
     * @param int $publish
     * @return bool
     * @throws Exception
     * @since 3.0
     */
	public function publish($cid = [], $publish = 1)
	{
		if (is_array($cid) && count($cid))
		{
			$tagIds = implode(',', $cid);

			$db = \JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->update($db->qn('#__product_tags'))
                ->set([
                    $db->qn('published') . ' = ' . $db->q((int) $publish)
                ])
                ->where($db->qn('tags_id') . ' IN (' . $db->q($tagIds) . ')');

			try {
			    $db->transactionStart();
			    $db->setQuery($query);
			    $db->execute();
            } catch (\RuntimeException $e) {
			    $db->transactionRollback();
			    \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			    return false;
            }

            return true;
		}

		return false;
	}
}
