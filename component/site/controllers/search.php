<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * search Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerSearch extends RedshopController
{
	/**
	 * cancel function
	 *
	 * @access public
	 * @return void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * loadProducts function
	 *
	 * @access public
	 * @return manufacturer select box
	 */
	public function loadProducts()
	{
		$get = JRequest::get('get');
		$taskid = $get['taskid'];

		$model = $this->getModel('search');

		$brands = $model->loadCatProductsManufacturer($taskid);

		// Manufacture Select Id
		$manufac_data = (JRequest::getInt('manufacture_id', 0));

		JLoader::import('joomla.application.module.helper');
		$module           = JModuleHelper::getModule('redshop_search');
		$params           = new JRegistry($module->params);
		$enableAjaxsearch = $params->get('enableAjaxsearch');
		$javaFun          = "";

		if ($enableAjaxsearch)
		{
			$javaFun = "makeUrl();";
		}

		if (count($brands) > 0)
		{
			$manufac     = array();
			$manufac[]   = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_MANUFACTURE'));
			$manufacdata = @array_merge($manufac, $brands);

			echo JText::_('COM_REDSHOP_SELECT_MANUFACTURE') . '<br/>' . JHTML::_('select.genericlist', $manufacdata, 'manufacture_id', 'class="inputbox" size="1" onChange="' . $javaFun . '" ', 'value', 'text', $manufac_data);
		}

		exit;
	}

	/**
	 * ajaxsearch function
	 *
	 * @access public
	 * @return search product results
	 */
	public function ajaxsearch()
	{
		$model  = $this->getModel('Search');
		$detail = $model->getajaxData();

		$encoded = json_encode($detail);
		ob_clean();
		echo "{\"results\": " . $encoded . "}";
		exit;
	}
}
