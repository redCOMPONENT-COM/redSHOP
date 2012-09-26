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

class sample_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'sample_detail');
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    public function save()
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $post ['sample_id'] = $cid [0];
        $link               = 'index.php?option=' . $option . '&view=sample';

        $model = $this->getModel('sample_detail');

        if ($model->store($post))
        {

            $msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_SAMPLE_DETAIL');
        }

        $this->setRedirect($link, $msg);
    }

    public function remove()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('sample_detail');

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=sample', $msg);
    }

    public function publish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('sample_detail');
        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_PUBLISHED_SUCCESFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=sample', $msg);
    }

    public function unpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('sample_detail');
        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_UNPUBLISHED_SUCCESFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=sample', $msg);
    }

    public function cancel()
    {
        $option = $this->input->get('option');
        $msg    = JText::_('COM_REDSHOP_SAMPLE_DETAIL_EDITING_CANCELLED');

        $this->setRedirect('index.php?option=' . $option . '&view=sample', $msg);
    }
}
