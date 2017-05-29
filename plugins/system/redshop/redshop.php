<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  System.RedSHOP
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgSystemRedSHOP class.
 *
 * @extends JPlugin
 *
 * @since   2.0.1
 */
class PlgSystemRedSHOP extends JPlugin
{
	/**
	 * Auto load language
	 *
	 * @var  string
	 */
	protected $autoloadLanguage = true;

	/**
	 * onAfterDispatch function.
	 *
	 * @return void
	 */
	public function onAfterDispatch()
	{
		if (!JFactory::getApplication()->isSite())
		{
			return;
		}

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

		if (JFactory::getApplication()->input->get('option') != 'com_redshop')
		{
			return;
		}

		$doc = new RedshopHelperDocument;
		$doc->cleanHeader();
	}
}
