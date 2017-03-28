<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class newsletterModelnewsletter
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelNewsletter extends RedshopModel
{
	public $_table_prefix = null;

	public $_db = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_db           = JFactory::getDbo();
		$this->_table_prefix = '#__redshop_';
		$sub_id              = JRequest::getInt('sid', '', 'request');

		if ($sub_id)
		{
			$this->confirmsubscribe($sub_id);
		}
	}

	public function checksubscriptionbymail($email)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$and  = "";

		if ($user->id)
		{
			$and .= "AND `user_id` = " . (int) $user->id . " ";
			$email = $user->email;
		}

		$query = "SELECT COUNT(*) FROM " . $this->_table_prefix . "newsletter";
		$this->_db->setQuery($query);
		$newsletter = $this->_db->loadResult();
		$url        = JURI::root();
		$link       = $url . 'index.php?option=com_redshop&view=newsletter';

		if ($newsletter != 0)
		{
			$query = "SELECT subscription_id FROM  " . $this->_table_prefix . "newsletter_subscription "
				. "WHERE email = " . $this->_db->quote($email) . " "
				. "AND newsletter_id = " . (int) Redshop::getConfig()->get('DEFAULT_NEWSLETTER') . " "
				. $and;
			$this->_db->setQuery($query);
			$alreadysub = $this->_db->loadResult();

			if ($alreadysub)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$app->redirect(JRoute::_($link), JText::_('COM_REDSHOP_NEWSLETTER_NOT_AVAILABLE'));
		}
	}

	public function confirmsubscribe($sub_id)
	{
		$app = JFactory::getApplication();
		$query = "UPDATE `" . $this->_table_prefix . "newsletter_subscription` SET `published` = '1' WHERE subscription_id = '" . (int) $sub_id . "' ";
		$this->_db->setQuery($query);
		$this->_db->execute();
		$url  = JURI::root();
		$link = $url . 'index.php?option=com_redshop&view=newsletter';
		$app->redirect(JRoute::_($link), JText::_('COM_REDSHOP_MESSAGE_CONFIRMED_SUBSCRIBE'));
	}
}
