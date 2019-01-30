<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Catalog view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewCatalog extends RedshopView
{
	/**
	 * @var  Joomla\Registry\Registry
	 */
	public $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed         A string if successful, otherwise a JError object.
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		/** @var JApplicationSite $app */
		$app = JFactory::getApplication();

		$params = $app->getParams('com_redshop');
		$layout = $app->input->getCmd('layout');

		if ($layout == "sample")
		{
			$this->setLayout('sample');
		}

		$this->params = $params;

		parent::display($tpl);
	}
}
