<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'default.php';

class product_categoryController extends RedshopCoreControllerDefault
{
    public function assignCategory()
    {
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    public function saveProduct_Category()
    {
        $model = $this->getModel("product_category");

        if ($model->saveProduct_Category())
        {
            $msg = JText::_('COM_REDSHOP_CATEGORY_ASSIGNED_TO_PRODUCT_SUCCESSFULLY');
        }

        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_WHILE_ASSIGNING_CATEGORY_TO_PRODUCT');
        }

        $this->app->redirect("index.php?option=com_redshop&view=product", $msg);
    }

    public function removeProduct_Category()
    {
        $model = $this->getModel("product_category");

        if ($model->removeProduct_Category())
        {
            $msg = JText::_('COM_REDSHOP_CATEGORY_REMOVED_FROM_PRODUCT_SUCCESSFULLY');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_WHILE_REMOVING_CATEGORY_FROM_PRODUCT');
        }

        $this->app->redirect("index.php?option=com_redshop&view=product", $msg);
    }
}
