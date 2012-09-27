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

class giftcard_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'giftcard_detail');
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
        $post["giftcard_desc"] = $this->input->post->getString('giftcard_desc', '');
        $showbuttons           = $this->input->get('showbuttons');
        $option                = $this->input->get('option');

        $model = $this->getModel('giftcard_detail');
        $row   = $model->store($post);

        if ($row)
        {

            $msg = JText::_('COM_REDSHOP_GIFTCARD_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_GIFTCARD');
        }
        if (!$showbuttons)
        {
            if ($apply == 1)
            {
                $this->setRedirect('index.php?option=' . $option . '&view=giftcard_detail&task=edit&cid[]=' . $row->giftcard_id, $msg);
            }
            else
            {
                $this->setRedirect('index.php?option=' . $option . '&view=giftcard', $msg);
            }
        }
        else
        {
            ?>
        <script language="javascript" type="text/javascript">
            window.parent.SqueezeBox.close();
        </script>
        <?php
        }
    }

    public function remove()
    {
        $option = $this->input->get('option');

        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('giftcard_detail');

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=giftcard');
    }

    public function publish()
    {

        $option = $this->input->get('option');

        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('giftcard_detail');

        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=giftcard');
    }

    public function unpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('giftcard_detail');

        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=giftcard');
    }

    public function cancel()
    {
        $option = $this->input->get('option');

        $this->setRedirect('index.php?option=' . $option . '&view=giftcard');
    }

    public function copy()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $model = $this->getModel('giftcard_detail');

        if ($model->copy($cid))
        {

            $msg = JText::_('COM_REDSHOP_GIFTCARD_COPIED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_COPYING_GIFTCARD');
        }

        $this->setRedirect('index.php?option=' . $option . '&view=giftcard', $msg);
    }
}
