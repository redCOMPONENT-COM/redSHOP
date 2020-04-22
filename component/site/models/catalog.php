<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

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
	/**
	 * @var null|string
	 */
	public $_table_prefix = null;

	/**
	 * RedshopModelCatalog constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	/**
	 * Method for store catalog
	 *
	 * @param   array  $data  Data
	 *
	 * @return  boolean
	 * @throws  Exception
	 */
	public function catalogStore($data)
	{
		/** @var Tablecatalog_request $row */
		$row = $this->getTable('catalog_request');

		if (!$row->bind($data))
		{
			/** @scrutinizer ignore-deprecated */$this->setError(/** @scrutinizer ignore-deprecated */$this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			/** @scrutinizer ignore-deprecated */$this->setError(/** @scrutinizer ignore-deprecated */$this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Method for store catalog sample
	 *
	 * @param   array  $data  Data
	 *
	 * @return  boolean
	 * @throws  Exception
	 */
	public function catalogSampleStore($data)
	{
		/** @var Tablesample_request $row */
		$row = $this->getTable('sample_request');

		if (!$row->bind($data))
		{
			/** @scrutinizer ignore-deprecated */$this->setError(/** @scrutinizer ignore-deprecated */$this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			/** @scrutinizer ignore-deprecated */$this->setError(/** @scrutinizer ignore-deprecated */$this->_db->getErrorMsg());

			return false;
		}

		return true;
	}
}
