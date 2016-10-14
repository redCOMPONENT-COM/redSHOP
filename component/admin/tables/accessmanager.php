<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopTableAccessmanager extends JTable
{
	/**
	 * @var   int
	 */
	public $id = null;

	/**
	 * @var   int
	 */
	public $section_name = null;

	/**
	 * @var   int
	 */
	public $gid = null;

	/**
	 * @var   int
	 */
	public $view = null;

	/**
	 * @var   int
	 */
	public $add = null;

	/**
	 * @var   int
	 */
	public $edit = null;

	/**
	 * @var   int
	 */
	public $delete = null;

	/**
	 * RedshopTableAccessmanager constructor.
	 *
	 * @param   string  &$db  Database object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__redshop_accessmanager', 'id', $db);
	}

	/**
	 * Bind data
	 *
	 * @param   array|object  $array   Data use for bind
	 * @param   string        $ignore  Ignore
	 *
	 * @return bool
	 */
	public function bind($array, $ignore = '')
	{
		if (array_key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Validate fields
	 *
	 * @return  bool
	 */
	public function check()
	{
		if (empty($this->section_name))
		{
			$this->setError('COM_REDSHOP_TABLE_ACCESSMANAGER_SECTION_IS_REQUIRED');

			return false;
		}

		$this->section_name = trim($this->section_name);

		if ($this->gid === null)
		{
			$this->gid = 0;
		}

		if ($this->view === null)
		{
			$this->view = 0;
		}

		if ($this->add === null)
		{
			$this->add = 0;
		}

		if ($this->edit === null)
		{
			$this->edit = 0;
		}

		if ($this->delete === null)
		{
			$this->delete = 0;
		}

		return parent::check();
	}
}
