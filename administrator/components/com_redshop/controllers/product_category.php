<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class product_categoryController extends RedshopCoreController
{
    public function assignCategory()
    {
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }

    public function saveProduct_Category()
    {
        global $mainframe;
        $model = $this->getModel("product_category");
        if ($model->saveProduct_Category())
        {
            $msg = JText::_('COM_REDSHOP_CATEGORY_ASSIGNED_TO_PRODUCT_SUCCESSFULLY');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_WHILE_ASSIGNING_CATEGORY_TO_PRODUCT');
        }
        $mainframe->redirect("index.php?option=com_redshop&view=product", $msg);
    }

    public function removeProduct_Category()
    {
        global $mainframe;
        $model = $this->getModel("product_category");
        if ($model->removeProduct_Category())
        {
            $msg = JText::_('COM_REDSHOP_CATEGORY_REMOVED_FROM_PRODUCT_SUCCESSFULLY');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_WHILE_REMOVING_CATEGORY_FROM_PRODUCT');
        }
        $mainframe->redirect("index.php?option=com_redshop&view=product", $msg);
    }
}
