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

class RedshopControllerCoupon_detail extends RedshopCoreController
{
    public $redirectViewName = 'coupon';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'coupon_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);

        $model     = $this->getModel('coupon_detail');
        $userslist = $model->getuserslist();
        $this->input->set('userslist', $userslist);

        $product = $model->getproducts();
        $this->input->set('product', $product);

        parent::display();
    }

    public function save($apply = 0)
    {
        $post            = $this->input->getArray($_POST);
        $post["comment"] = $this->input->post->getString('comment', '');

        $option = $this->input->get('option');

        $cid = $this->input->post->get('cid', array(0), 'array');

        $post ['coupon_id']  = $cid [0];
        $post ['start_date'] = strtotime($post ['start_date']);

        if ($post ['end_date'])
        {
            $post ['end_date'] = strtotime($post ['end_date']) + (23 * 59 * 59);
        }

        $model = $this->getModel('coupon_detail');

        if ($post['old_coupon_code'] != $post['coupon_code'])
        {
            if ($model->checkduplicate($post['coupon_code']))
            {
                $msg = JText::_('COM_REDSHOP_CODE_IS_ALREADY_IN_USE');
                $this->app->redirect('index.php?option=' . $option . '&view=coupon_detail&task=edit&cid=' . $post ['coupon_id'], $msg);
            }
        }

        if ($model->store($post))
        {

            $msg = JText::_('COM_REDSHOP_COUPON_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_COUPON_DETAIL');
        }

        $this->setRedirect('index.php?option=' . $option . '&view=coupon', $msg);
    }
}
