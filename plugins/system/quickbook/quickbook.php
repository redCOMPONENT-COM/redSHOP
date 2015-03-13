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
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Trigger function on before render
	 *
	 * @return  void
	 */
	public function onBeforeRender()
	{
		$app        = JFactory::getApplication();
		$option     = $app->input->getCmd('option', '');
		$control    = $app->input->getCmd('control', '');
		$secretWord = $app->input->getCmd('secret', '');

		if ($option == 'com_redshop'
			&& $secretWord == $this->params->get('secretWord'))
		{
			if ($app->isAdmin())
			{
				$this->$control();
			}
			else
			{
				// Set 'Site' suffix for frontend functions
				$siteMethod = $control . 'Site';

				$this->$siteMethod();
			}
		}
	}

	/**
	 * Set connection ticket from Intuit Quickbook Subscription Url and write into JSON file
	 *
	 * @return  void
	 */
	private function setConnectionTicketSite()
	{
		jimport( 'joomla.filesystem.file' );

		$buffer = json_encode($_REQUEST);

		// Write connection ticket outside of site root
		JFile::write(JPATH_SITE . self::CONNECTION_TICKET_PATH, $buffer);

		JFactory::getApplication()->close();
	}

	/**
	 * Read the JSON file and get connection ticket.
	 *
	 * @return  void
	 */
	private function getConnectionTicket()
	{
		jimport('joomla.filesystem.file');

		$data = JFile::read(JPATH_SITE . self::CONNECTION_TICKET_PATH);
		$jsonObject = json_decode($data);

		if (isset($jsonObject->conntkt))
		{
			echo $data;
		}
		else
		{
			echo JText::_('PLG_SYS_QUICKBOOK_GET_CONNECTION_TICKET_FAIL');
		}

		JFactory::getApplication()->close();
	}

	private function generatePrivateKey()
	{
		$basePath       = realpath(JPATH_SITE . '/../');
		$privateKeyPath = $basePath . '/quickbook_private.key';

		shell_exec('openssl genrsa -out ' . $privateKeyPath . ' 1024');

		if (JFile::exists($privateKeyPath))
		{
			echo json_encode(
					array(
						'key' => JFile::read($privateKeyPath)
					)
				);
			JFile::delete($privateKeyPath);
		}
		else
		{
			echo JText::_('PLG_SYS_QUICKBOOK_GET_PRIVATE_KEY_FAIL');
		}

		JFactory::getApplication()->close();
	}

	private function generatePem()
	{
		$app = JFactory::getApplication();

		$certData = $app->input->getVar('certData');

		$basePath        = realpath(JPATH_SITE . '/../');
		$certificatePath = $basePath . '/quickbook_certificate.pem';

		if (JFile::write($certificatePath, $certData))
		{
			echo json_encode(array('success' => true, 'path' => $certificatePath));
		}
		else
		{
			echo JText::_('PLG_SYS_QUICKBOOK_GET_CERTIFICATE_FAIL');
		}

		$app->close();
	}
}
