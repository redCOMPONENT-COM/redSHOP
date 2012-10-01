<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class discount_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'currency';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $layout = $this->input->get('layout');

        $this->input->set('view', 'discount_detail');

        if ($layout == 'product')
        {
            $this->input->set('layout', 'product');
        }
        else
        {
            $this->input->set('layout', 'default');
        }
        $this->input->set('hidemainmenu', 1);

        parent::display();
    }

    public function save($apply = 0)
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');
        $layout = $this->input->get('layout');

        $post ['start_date'] = strtotime($post ['start_date']);
        $post ['end_date']   = strtotime($post ['end_date']) + (23 * 59 * 59);

        $model = $this->getModel('discount_detail');

        if (isset($layout) && $layout == 'product')
        {
            $post ['discount_product_id'] = $cid[0];
            $row                          = $model->storeDiscountProduct($post);
            $did                          = $row->discount_product_id;
        }
        else
        {

            $post ['discount_id'] = $cid[0];
            $row                  = $model->store($post);
            $did                  = $row->discount_id;
        }
        if ($row)
        {
            $model->saveShoppers($did, $post['shopper_group_id']);
            $msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_DISCOUNT_DETAIL');
        }
        if ($apply == 1)
        {
            if (isset($layout) && $layout == 'product')
            {
                $this->setRedirect('index.php?option=' . $option . '&view=discount_detail&layout=product&task=edit&cid[]=' . $row->discount_product_id, $msg);
            }
            else
            {
                $this->setRedirect('index.php?option=' . $option . '&view=discount_detail&task=edit&cid[]=' . $row->discount_id, $msg);
            }
        }
        else
        {
            if (isset($layout) && $layout == 'product')
            {
                $this->setRedirect('index.php?option=' . $option . '&view=discount&layout=product', $msg);
            }
            else
            {
                $this->setRedirect('index.php?option=' . $option . '&view=discount', $msg);
            }
        }
    }

    public function remove()
    {
        // The remove logic is the same in the parent, just the redirection changes.
        parent::remove();

        $layout = $this->input->get('layout');
        $msg    = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_DELETED_SUCCESSFULLY');

        if (isset($layout) && $layout == 'product')
        {
            $this->setRedirect('index.php?option=com_redshop&view=discount&layout=product', $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=com_redshop&view=discount', $msg);
        }
    }

    public function publish()
    {
        // The publish logic is the same in the parent, just the redirection changes.
        parent::publish();

        $layout = $this->input->get('layout');
        $msg    = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_PUBLISHED_SUCCESSFULLY');

        if (isset($layout) && $layout == 'product')
        {
            $this->setRedirect('index.php?option=com_redshop&view=discount&layout=product', $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=com_redshop&view=discount', $msg);
        }
    }

    public function unpublish()
    {
        // The unpublish logic is the same in the parent, just the redirection changes.
        parent::unpublish();

        $layout = $this->input->get('layout');
        $msg    = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_UNPUBLISHED_SUCCESSFULLY');

        if (isset($layout) && $layout == 'product')
        {
            $this->setRedirect('index.php?option=com_redshop&view=discount&layout=product', $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=com_redshop&view=discount', $msg);
        }
    }

    public function cancel()
    {
        $layout = $this->input->get('layout');
        $option = $this->input->get('option');

        $msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_EDITING_CANCELLED');

        if (isset($layout) && $layout == 'product')
        {
            $this->setRedirect('index.php?option=' . $option . '&view=discount&layout=product', $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=discount', $msg);
        }
    }
}
