<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Controller Template Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.7
 */
class RedshopControllerTemplate extends RedshopControllerForm
{
	/**
	 * Method for live render
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function liveRender()
	{
		Redshop\Helper\Ajax::validateAjaxRequest();

		$templateSection = $this->input->getString('section', 'giftcard_list');
		$templateContent = $this->input->get('content', '', 'Raw');

		echo trim(RedshopHelperTwig::liveRender($templateSection, $templateContent));

		JFactory::getApplication()->close();
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.1.0
	 */
	public function getModel($name = 'Template', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}
