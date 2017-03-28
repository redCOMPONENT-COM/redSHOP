<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablenewslettersubscr_detail extends JTable
{
	public $subscription_id = null;

	public $user_id = null;

	public $date = null;

	public $newsletter_id = null;

	public $name = null;

	public $email = null;

	public $published = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'newsletter_subscription', 'subscription_id', $db);
	}

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
	 * Method to store a row in the database from the JTable instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.
	 * If no primary key value is set a new row will be inserted into the database with the properties from the JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	public function store($updateNulls = false)
	{
		$isNew = empty($this->subscription_id) ? true : false;

		if (!parent::store($updateNulls))
		{
			return false;
		}

		JPluginHelper::importPlugin('redshop_user');
		RedshopHelperUtility::getDispatcher()->trigger('addNewsLetterSubscription', array($isNew, $this->getProperties()));

		return true;
	}

	/**
	 * Method to delete a row from the database table by primary key value.
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 * @throws  UnexpectedValueException
	 */
	public function delete($pk = null)
	{
		$data = $this->getProperties();

		if (!parent::delete($pk))
		{
			return false;
		}

		JPluginHelper::importPlugin('redshop_user');
		RedshopHelperUtility::getDispatcher()->trigger('removeNewsLetterSubscription', array($data));

		return true;
	}
}
