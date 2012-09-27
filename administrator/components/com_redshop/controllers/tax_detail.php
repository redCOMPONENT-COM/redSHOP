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

class tax_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'tax_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);

        parent::display();
    }

    public function save()
    {
        $post         = $this->input->getArray($_POST);
        $option       = $this->input->get('option');
        $tax_group_id = $this->input->get('tax_group_id');

        $model = $this->getModel('tax_detail');

        if ($model->store($post))
        {

            $msg = JText::_('COM_REDSHOP_TAX_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_TAX_DETAIL');
        }

        if (isset($post['tmpl']) && $post['tmpl'] == "component")
        {
            ?>
        <script>
            window.parent.document.getElementById('installform').substep.value = 4;
            window.parent.document.getElementById('installform').submit();
            window.parent.SqueezeBox.close();
        </script>
        <?php
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=tax&tax_group_id=' . $tax_group_id, $msg);
        }
    }

    public function remove()
    {
        $option       = $this->input->get('option');
        $tax_group_id = $this->input->get('tax_group_id');
        $cid          = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('tax_detail');

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_TAX_DETAIL_DELETED_SUCCESSFULLY');

        $this->setRedirect('index.php?option=' . $option . '&view=tax&tax_group_id=' . $tax_group_id, $msg);
    }

    public function removefromwizrd()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('tax_detail');
        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&step=4');
    }

    public function cancel()
    {
        $option       = $this->input->get('option');
        $tax_group_id = $this->input->get('tax_group_id');

        $msg = JText::_('COM_REDSHOP_TAX_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=tax&tax_group_id=' . $tax_group_id, $msg);
    }
}
