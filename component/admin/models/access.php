<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Model Access
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.6
 */

class RedshopModelAccess extends RedshopModelForm
{
	/**
	 * @var  string
	 */
	protected $assetName = 'com_redshop.backend';

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   12.2
	 */
	public function save($data)
	{
		if (empty($data) || empty($data['rules']) || !JFactory::getUser()->authorise('core.manage', 'com_redshop'))
		{
			return false;
		}

		$rules = $data['rules'];
		$rules = array_map(function($item){ return array_filter($item, 'strlen'); }, $rules);
		$rules = new JAccessRules($rules);
		unset($data['rules']);

		/** @var RedshopTableAccess $table */
		$table = $this->getTable();

		if (!$table->load($data['id']) || empty($table->parent_id))
		{
			/** @var JTableAsset $root */
			$root = JTable::getInstance('asset');
			$root->loadByName('com_redshop');

			$table->setLocation($root->id, 'last-child');
			$table->parent_id = $root->id;
		}

		$table->name  = $this->assetName;
		$table->title = $data['title'];
		$table->rules = (string) $rules;

		if (!$table->check() || !$table->store())
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  object|boolean  Object on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItem($pk = null)
	{
		$table = $this->getTable();

		if (!$table->loadByName($this->assetName))
		{
			return false;
		}

		$item = $table->getProperties(true);
		$item = ArrayHelper::toObject($item, 'JObject');

		return $item;
	}
}
