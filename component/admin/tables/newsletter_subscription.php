<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The Newsletter Subscription table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Catalog
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableNewsletter_Subscription extends RedshopTable
{
	public $user_id = null;

	public $date = '0000-00-00';

	public $name = null;

	public $email = null;

	public $published = null;

	/**
	 * The table name without prefix.
	 *
	 * @var string
	 */
	protected $_tableName = 'redshop_newsletter_subscription';

	/**
	 * Do the database store.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean
	 */
	public function doStore($updateNulls = false)
	{
		$app        = JFactory::getApplication();
		$data       = $app->input->post->get('jform', array(), 'array');

		if (empty($data['name'])) {
			$this->name = \RedshopEntityNewsletter_Subscription::getUserFullName($data['user_id']);
		}

		return parent::doStore($updateNulls);
	}

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		$app        = JFactory::getApplication();
		$data       = $app->input->post->get('jform', array(), 'array');

		if (($data['email'] == '') || !JMailHelper::isEmailAddress($data['email']))
		{
			$this->setError(\JText::_('JLIB_DATABASE_ERROR_VALID_MAIL'));

			return false;
		}

		return parent::doCheck();
	}
}
