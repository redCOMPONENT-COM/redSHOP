<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_login
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

class modRedshopLoginHelper
{
	function getReturnURL($params, $type)
	{
		if ($itemid = $params->get($type))
		{
			$menu = JSite::getMenu();
			$item = $menu->getItem($itemid);
			$url  = JRoute::_($item->link . '&Itemid=' . $itemid, false);
		}
		else
		{
			// stay on the same page
			$uri = JFactory::getURI();
			$url = JFilterOutput::cleanText($uri->toString(array('path', 'query', 'fragment')));
		}

		return base64_encode($url);
	}

	function getType()
	{
		$user = JFactory::getUser();

		return (!$user->get('guest')) ? 'logout' : 'login';
	}
}
