<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
use Joomla\Utilities\ArrayHelper;
/**
 * Categories list controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Categories
 * @since       2.0.6
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
	 * @since   2.0.6
	 */
	public function getModel($name = 'Category', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * assign template to multiple categories
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
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
    public function publish()
    {
        JPluginHelper::importPlugin('category');
        JPluginHelper::importPlugin('logman');
        $dispatcher = RedshopHelperUtility::getDispatcher();

        $value = ArrayHelper::getValue($this->states, $this->getTask(), 0, 'int');

        // call function Publish form class RedshopControllerAdminBase
        RedshopControllerAdminBase::publish();

        if($value==1)
        {
            $dispatcher->trigger('onAfterAdminPublishCategory', array());
        }else{
            $dispatcher->trigger('onAfterAdminUnpublishCategory', array());
        }

    }
    public function delete()
    {
        JPluginHelper::importPlugin('category');
        JPluginHelper::importPlugin('logman');
        $dispatcher = RedshopHelperUtility::getDispatcher();

        RedshopControllerAdminBase::delete();

        $dispatcher->trigger('onAfterAdminDeleteCategory', array());
    }
}

