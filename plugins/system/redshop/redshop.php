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
	}

	/**
	 * onBeforeCompileHead function
	 *
	 * @return  void
	 */
	public function onBeforeCompileHead()
	{
		if (class_exists('RedshopHelperConfig'))
		{
			RedshopHelperConfig::scriptDeclaration();
		}

		$app = JFactory::getApplication();
		$jinput = $app->input;

		if ($jinput->get('option') != 'com_redshop')
		{
			return;
		}

		$doc = new RedshopHelperDocument;
		$doc->cleanHeader();
	}
}
