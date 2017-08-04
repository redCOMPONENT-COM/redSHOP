<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Site;

defined('_JEXEC') or die;

/**
 * Class catalogModelcatalog
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelCatalog extends RedshopModel
{
	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	public function catalogStore($data)
	{
		$row = $this->getTable('catalog_request');

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

	public function catalogSampleStore($data)
	{
		$row = $this->getTable('sample_request');

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

	public function getCatalogList()
	{
		$query   = "SELECT c.*,c.catalog_id AS value,c.catalog_name AS text FROM " . $this->_table_prefix . "catalog AS c "
			. "WHERE c.published = 1 ";
		$catalog = $this->_getList($query);

		return $catalog;
	}

	public function getCatalogSampleList()
	{
		$query   = "SELECT c.* FROM " . $this->_table_prefix . "catalog_sample AS c "
			. "WHERE c.published = 1 ";
		$catalog = $this->_getList($query);

		return $catalog;
	}

	public function getCatalogSampleColorList($sample_id = 0)
	{
		$and = "";

		if ($sample_id != 0)
		{
			$and = "AND c.sample_id = " . (int) $sample_id . " ";
		}

		$query   = "SELECT c.* FROM " . $this->_table_prefix . "catalog_colour AS c "
			. "WHERE 1=1 "
			. $and;
		$catalog = $this->_getList($query);

		return $catalog;
	}
}
