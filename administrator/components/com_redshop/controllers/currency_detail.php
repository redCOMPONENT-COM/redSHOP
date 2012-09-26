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

class currency_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'currency_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);

        parent::display();
    }

    public function apply()
    {
        $this->save(1);
    }

    public function save($apply = 0)
    {
        $post                  = $this->input->getArray($_POST);
        $post["currency_name"] = $this->input->post->getString('currency_name', '');
        $option                = $this->input->get('option');
        $cid                   = $this->input->post->get('cid', array(0), 'array');
        $post ['currency_id']  = $cid [0];
        $model                 = $this->getModel('currency_detail');
        $row                   = $model->store($post);

        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_CURRENCY_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_CURRENCY_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=currency_detail&task=edit&cid[]=' . $row->currency_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=currency', $msg);
        }
    }

    public function cancel()
    {
        $option = $this->input->get('option');

        $msg = JText::_('COM_REDSHOP_CURRENCY_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=currency', $msg);
    }

    public function remove()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('currency_detail');

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_CURRENCY_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=currency', $msg);
    }
}
