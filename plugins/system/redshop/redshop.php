<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  System.RedSHOP
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgSystemRedSHOP class.
 *
 * @extends JPlugin
 * @since  1.5.0.1
 */
class PlgSystemRedSHOP extends JPlugin
{
	protected $autoloadLanguage = true;

	/**
	 * onAfterDispatch function.
	 *
	 * @return void
	 */
	public function onAfterDispatch()
	{
		$app = JFactory::getApplication();
		JLoader::import('redshop.library');

		// Load only from frontend
		if (!$app->isAdmin())
		{
			$option = $app->input->getCmd('option', '');
			$view = $app->input->getCmd('view', '');

			// Redirect for use redSHOP registration instead com_users
			if ($option == 'com_users' && $view == 'registration')
			{
				JLoader::load('RedshopHelperHelper');
				$redHelper = new redhelper;
				$items = $redHelper->getRedshopMenuItems();
				$itemId = null;

				// Search for a suitable menu id.
				foreach ($items as $item)
				{
					if (isset($item->query['view']) && $item->query['view'] === 'registration')
					{
						$itemId = $item->id;
						break;
					}
				}

				$app->redirect(JRoute::_('index.php?option=com_redshop&view=registration&Itemid=' . $itemId, false));
			}

			JLoader::load('RedshopHelperRedshop.js');

			// Use different CSS for print layout
			if ($app->input->getCmd('print', ''))
			{
				JHtml::stylesheet('com_redshop/print.css', array(), true);
			}

			JHtml::stylesheet('com_redshop/redshop.css', array(), true);
			JHtml::stylesheet('com_redshop/style.css', array(), true);
		}
	}

	/**
	 * onAfterRender function
	 *
	 * @return  void
	 */
	public function onBeforeCompileHead()
	{
		if (class_exists('RedshopConfig'))
		{
			RedshopConfig::scriptDeclaration();
		}
	}
}
