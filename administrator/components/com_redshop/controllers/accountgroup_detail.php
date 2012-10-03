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

class accountgroup_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'accountgroup';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $post ['accountgroup_id'] = $cid [0];
        $model                    = $this->getModel('accountgroup_detail');
        $row                      = $model->store($post);

        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_ACCOUNTGROUP_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_ACCOUNTGROUP_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=accountgroup_detail&task=edit&cid[]=' . $row->accountgroup_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=accountgroup', $msg);
        }
    }
}

