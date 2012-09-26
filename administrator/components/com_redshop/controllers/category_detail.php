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

class category_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'category_detail');
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
        $post                       = $this->input->get('post');
        $category_description       = $this->input->post->getString('category_description', '');
        $category_short_description = $this->input->post->getString('category_short_description', '');

        $post["category_description"] = $category_description;

        $post["category_short_description"] = $category_short_description;

        if (is_array($post["category_more_template"]))
        {
            $post["category_more_template"] = implode(",", $post["category_more_template"]);
        }

        $option               = $this->input->get('option');
        $cid                  = $this->input->post->get('cid', array(0), 'array');
        $post ['category_id'] = $cid [0];
        $model                = $this->getModel('category_detail');

        if ($row = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_CATEGORY_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=category_detail&task=edit&cid[]=' . $row->category_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
        }
    }

    public function remove()
    {
        $option = $this->input->get('option');

        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('category_detail');

        if (!$model->delete($cid))
        {
            $msg = "";
            if ($model->getError() != "")
            {
                JError::raiseWarning(500, $model->getError());
            }
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_DELETED_SUCCESSFULLY');
        }

        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }

    public function publish()
    {
        $option = $this->input->get('option');

        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('category_detail');

        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_PUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }

    public function unpublish()
    {

        $option = $this->input->get('option');

        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('category_detail');
        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }
        $msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_UNPUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }

    public function cancel()
    {

        $option = $this->input->get('option');
        $msg    = JText::_('COM_REDSHOP_CATEGORY_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }

    public function orderup()
    {
        $option = $this->input->get('option');

        $model = $this->getModel('category_detail');
        $model->orderup();

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }

    public function orderdown()
    {
        $option = $this->input->get('option');

        $model = $this->getModel('category_detail');
        $model->orderdown();

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }

    public function saveorder()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(), 'array');
        $order  = $this->input->post->get('order', array(), 'array');

        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('category_detail');
        $model->saveorder($cid, $order);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }

    public function copy()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');
        $model  = $this->getModel('category_detail');

        if ($model->copy($cid))
        {
            $msg = JText::_('COM_REDSHOP_CATEGORY_COPIED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_COPING_CATEGORY');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
    }
}
