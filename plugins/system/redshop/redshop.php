<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  System.RedSHOP
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
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

		RedshopHelperJs::init();

		// Load only from frontend
		if (!$app->isAdmin())
		{
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
		if (class_exists('RedshopHelperConfig'))
		{
			RedshopHelperConfig::scriptDeclaration();
		}
	}
}
