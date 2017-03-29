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
 * Categories list controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Categories
 * @since       2.0.4
 */
class RedshopControllerCategories extends RedshopControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.0.0.2
	 */
	public function getModel($name = 'Category', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * assign template to multiple categories
	 *
	 * @since   2.0.0.2
	 */
	public function assignTemplate()
	{
		$input                     = JFactory::getApplication()->input;
		$cid                       = $input->post->get('cid', array(), 'array');
		$filter                    = $input->post->get('filter', array(), 'array');
		$post                      = array();
		$post['cid']               = $cid;
		$post['category_template'] = $filter['category_template'];
		$model                     = $this->getModel('categories');

		if ($model->assignTemplate($post))
		{
			$msg = JText::_('COM_REDSHOP_TEMPLATE_ASSIGN_SUCESS');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_ASSIGNING_TEMPLATE');
		}

		$this->setRedirect('index.php?option=com_redshop&view=categories', $msg);
	}

	/**
	 * save ordering
	 *
	 * @since   2.0.0.2
	 */
	public function saveorder()
	{
		$input = JFactory::getApplication()->input;
		$cid   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('category');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=categories', $msg);
	}
}

