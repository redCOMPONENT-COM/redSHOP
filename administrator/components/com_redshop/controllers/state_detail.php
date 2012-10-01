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

class state_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'state';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post       = $this->input->getArray($_POST);
        $state_name = $this->input->post->getString('state_name', '');

        $post["state_name"] = $state_name;
        $option             = $this->input->get('option');
        $cid                = $this->input->post->get('cid', array(0), 'array');
        $post ['state_id']  = $cid [0];
        $model              = $this->getModel('state_detail');
        $row                = $model->store($post);
        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_STATE_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_IN_STATE_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=state_detail&task=edit&cid[]=' . $row->state_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=state', $msg);
        }
    }

    public function cancel()
    {
        $option = $this->input->get('option');

        $model = $this->getModel('state_detail');
        $model->checkin();

        $msg = JText::_('COM_REDSHOP_state_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=state', $msg);
    }
}
