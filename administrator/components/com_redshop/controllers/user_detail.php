<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class user_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
        $this->_table_prefix = '#__redshop_';
        $this->redhelper     = new redhelper();
    }

    public function edit()
    {
        $this->input->set('view', 'user_detail');
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
        $option = $this->input->getString('option', '');
        $post   = $this->input->getArray($_POST);

        $model    = $this->getModel('user_detail');
        $shipping = isset($post["shipping"]) ? true : false;

        if ($row = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_USER_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_USER_DETAIL');
        }

        if ($shipping)
        {
            $info_id = $this->input->getString('info_id', '');
            $link    = 'index.php?option=' . $option . '&view=user_detail&task=edit&cancel=1&cid[]=' . $info_id;
        }
        else
        {
            if ($apply == 1)
            {
                $link = 'index.php?option=' . $option . '&view=user_detail&task=edit&cid[]=' . $row->users_info_id;
                $link = $this->redhelper->sslLink($link);
            }
            else
            {
                $link = 'index.php?option=' . $option . '&view=user';
                $link = $this->redhelper->sslLink($link, 0);
            }
        }
        $this->setRedirect($link, $msg);
    }

    public function remove()
    {
        $option   = $this->input->getString('option', '');
        $shipping = $this->input->getString('shipping', '');
        $cid      = $this->input->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('user_detail');
        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_USER_DETAIL_DELETED_SUCCESSFULLY');

        if ($shipping)
        {
            $info_id = $this->input->getInt('info_id', '');
            $this->setRedirect('index.php?option=' . $option . '&view=user_detail&task=edit&cancel=1&cid[]=' . $info_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=user', $msg);
        }
    }

    public function publish()
    {
        $option = $this->input->getString('option', '');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('user_detail');
        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_USER_DETAIL_PUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=user', $msg);
    }

    public function unpublish()
    {
        $option = $this->input->getString('option', '');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('user_detail');
        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_USER_DETAIL_UNPUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=user', $msg);
    }

    public function cancel()
    {
        $option   = $this->input->getString('option', '');
        $shipping = $this->input->getString('shipping', '');
        $info_id  = $this->input->getString('info_id', '');

        $msg = JText::_('COM_REDSHOP_USER_DETAIL_EDITING_CANCELLED');
        if ($shipping)
        {
            $link = 'index.php?option=' . $option . '&view=user_detail&task=edit&cancel=1&cid[]=' . $info_id;
        }
        else
        {
            $link = 'index.php?option=' . $option . '&view=user';
        }

        $link = $this->redhelper->sslLink($link, 0); //not to apply ssl (passed Zero)
        $this->setRedirect($link, $msg);
    }

    public function order()
    {
        $option  = $this->input->getString('option', '');
        $user_id = $this->input->getInt('user_id', 0);

        $this->setRedirect('index.php?option=' . $option . '&view=addorder_detail&user_id=' . $user_id);
    }

    public function validation()
    {
        $json             = $this->input->get('json', '');
        $decoded          = json_decode($json);
        $model            = $this->getModel('user_detail');
        $username         = $model->validate_user($decoded->username, $decoded->userid);
        $email            = $model->validate_email($decoded->email, $decoded->userid);
        $json             = array();
        $json['ind']      = $decoded->ind;
        $json['username'] = $username;
        $json['email']    = $email;
        $encoded          = json_encode($json);
        die($encoded);
    }
}
