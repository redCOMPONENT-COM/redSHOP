<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'category.php');

class RedshopViewProduct_category extends JViewLegacy
{
    var $_product = array();

    public function display($tpl = null)
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_PRODUCT'));
        $task = JRequest::getVar('task');
        JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT'), 'redshop_products48');

        if ($task == 'assignCategory')
        {
            JToolBarHelper::custom('saveProduct_Category', 'save.png', 'save_f2.png', JText :: _('COM_REDSHOP_ASSIGN_CATEGORY'), false);
        }
        else
        {
            JToolBarHelper::custom('removeProduct_Category', 'delete.png', 'delete.png', JText :: _('COM_REDSHOP_REMOVE_CATEGORY'), false);
        }

        JToolBarHelper::back();

        $model    = $this->getModel("product_category");
        $products = $model->getProductlist();

        $product_category = new product_category();
        $categories       = $product_category->getCategoryListArray();

        $temps                   = array();
        $temps[0]->category_id   = "0";
        $temps[0]->category_name = JText::_('COM_REDSHOP_SELECT');
        $categories              = @array_merge($temps, $categories);

        $lists['category'] = JHTML::_('select.genericlist', $categories, 'category_id[]', 'class="inputbox" multiple="multiple"', 'category_id', 'category_name');

        $this->assignRef('products', $products);
        $this->assignRef('lists', $lists);
        parent::display($tpl);
    }
}
