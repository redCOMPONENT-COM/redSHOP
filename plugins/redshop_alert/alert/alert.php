<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

/**
 * Plugins redSHOP Alert
 *
 * @since  1.0
 */
class PlgRedshop_AlertAlert extends JPlugin
{
	/**
	 * Constructor - note in Joomla 2.5 PHP4.x is no longer supported so we can use this.
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * store alert function
	 *
	 * @param   string  $message  alert message
	 *
	 * @return boolean
	 */
	public function storeAlert($message)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->insert($db->qn('#__redshop_alerts'))
			->columns($db->qn(['message', 'sent_date', 'read']))
			->values($db->q($message) . ',' . $db->q(date('Y-m-d H:i:s')) . ',' . $db->q('0'));

		return $db->setQuery($query)->execute();
	}

	/**
	 * send mail to admin
	 *
	 * @param   string  $message  alert message
	 *
	 * @return boolean
	 */
	public function sendEmail($message)
	{
		$config = JFactory::getConfig();
		$mailer = JFactory::getMailer();
		$mailer->isHTML(true);

		$adminEmail = trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL'));

		$sent = $mailer->sendMail(
			$config->get('mailfrom'),
			$config->get('fromname'),
			$adminEmail,
			JText::_('COM_REDSHOP_ALERT_STOCKROOM_BELOW_AMOUNT_NUMBER_MAIN_SUBJECT'),
			$message,
			1
		);

		return $sent;
	}
}
