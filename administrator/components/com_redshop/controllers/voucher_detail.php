<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class RedshopControllerVoucher_detail extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'voucher';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post               = $this->input->getArray($_POST);
        $option             = $this->input->getString('option', '');
        $cid                = $this->input->post->get('cid', array(0), 'array');
        $post['start_date'] = strtotime($post['start_date']);

        if ($post ['end_date'])
        {
            $post ['end_date'] = strtotime($post ['end_date']) + (23 * 59 * 59);
        }

        $post ['voucher_id'] = $cid[0];
        $model               = $this->getModel('voucher_detail');
        if ($post['old_voucher_code'] != $post['voucher_code'])
        {
            $code = $model->checkduplicate($post['voucher_code']);
            if ($code)
            {
                $msg = JText::_('COM_REDSHOP_CODE_IS_ALREADY_IN_USE');
                $this->app->redirect('index.php?option=' . $option . '&view=voucher_detail&task=edit&cid=' . $post ['voucher_id'], $msg);
            }
        }

        if ($row = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_VOUCHER_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=voucher_detail&task=edit&cid[]=' . $row->voucher_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=voucher', $msg);
        }
    }
}
