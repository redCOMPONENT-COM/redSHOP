<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Joomla! System redproductzoom Me Plugin
 *
 * @package        RedSHOP.Plugin
 * @subpackage     System.redproductzoom
 */
class plgSystemredproductzoom extends JPlugin
{
	public function onBeforeRender()
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;

		// No redproductzoom me for admin
		if ($app->isAdmin())
		{
			return;
		}

		if ($jinput->get('option') != 'com_redshop')
		{
			return;
		}
		else
		{
			$isChilds       = false;
			$attributes_set = array();

		if ($jinput->get('view') != 'product')
		{
			return;
		}

		if ($jinput->get('pid', 0, 'INT') == 0)
		{
			return;
		}

		$document = JFactory::getDocument();
		$url = JURI::base(true) . '/plugins/system/redproductzoom';

		$document->addScript($url . '/js/jquery.js');
		$document->addScript($url . '/js/jquery.elevateZoom.min.js');
		$document->addScript($url . '/js/redproductzoom.js');
	}
}
