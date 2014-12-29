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
 * Joomla! System redproductzoom Plugin
 *
 * @package     RedSHOP.Plugin
 * @subpackage  System.redproductzoom
 * @since       1.3.3.1
 */
class PlgSystemredproductzoom extends JPlugin
{
	/**
	 * This event is triggered immediately before pushing the document buffers into the template placeholders,
	 * retrieving data from the document and pushing it into the into the JResponse buffer.
	 * http://docs.joomla.org/Plugin/Events/System
	 *
	 * @return void
	 */
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

			JHtml::_('redshopjquery.framework');
			$document->addScript($url . '/js/jquery.elevateZoom.min.js');
			$document->addScript($url . '/js/redproductzoom.js');
		}
	}
}
