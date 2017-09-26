<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Plugin will generate XML for Websosanh
 *
 * @since  1.1.20
 */
class PlgRedshop_ProductWss_Datafeed extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An optional associative array of configuration settings
	 *
	 * @since   1.0.0
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_product_wss_datafeed', JPATH_ADMINISTRATOR);
		$lang->load('com_redshop', JPATH_SITE);

		parent::__construct($subject, $config);
	}
}
