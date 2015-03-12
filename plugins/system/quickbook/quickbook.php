<?php

/**
 * @package     System
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2014 redweb.dk. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Plugin to handle quickbook payments
 *
 * @since  2.0
 */
class plgSystemQuickbook extends JPlugin
{
	/**
	 * Connection ticket JSON file default path from  site root - JPATH_SITE
	 */
	const CONNECTION_TICKET_PATH = '/../connectionticket.json';

	/**
	 * Trigger function on before render
	 *
	 * @return  void
	 */
	public function onBeforeRender()
	{
		$app        = JFactory::getApplication();
		$option     = $app->input->getCmd('option', '');
		$view       = $app->input->getCmd('view', '');
		$control    = $app->input->getCmd('control', '');
		$secretWord = $app->input->getCmd('secret', '');

		if ($option == 'com_redshop'
			&& $view == 'redshop'
			&& $secretWord == $this->params->get('secretWord')
			&& $app->isAdmin())
		{
			$this->$control();
		}
	}

	/**
	 * Read the JSON file and get connection ticket.
	 *
	 * @return  void
	 */
	public function getConnectionTicket()
	{
		jimport('joomla.filesystem.file');

		$data = JFile::read(JPATH_SITE . self::CONNECTION_TICKET_PATH);

		if ($data)
		{
			echo $data;
		}
		else
		{
			echo JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_GET_CONNECTION_TICKET_FAIL');
		}

		JFactory::getApplication()->close();
	}
}
