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

class container_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $model          = $this->getModel('container_detail');
        $stockroom_data = $model->stockroom_data($id = 0);
        $this->input->set('stockroom_data', $stockroom_data);
        $this->input->set('view', 'container_detail');
        $this->input->set('hidemainmenu', 1);

        parent::display();
    }

    public function addcontainer()
    {
        $conid = $this->input->post->get('cid', array(0), 'array');
        $this->input->set('conid', $conid);

        parent::display();
    }

    public function saveanddisplay()
    {
        $post         = $this->input->getArray($_GET);
        $model        = $this->getModel('container_detail');
        $container_id = $model->saveanddisplay($post);
        $this->setRedirect('index.php?tmpl=component&option=com_redshop&view=container_detail&layout=products&rand_id=' . time() . '&task=edit&cid[]=' . $container_id);
    }

    public function deleteProduct()
    {
        $post  = $this->input->getArray($_GET);
        $model = $this->getModel('container_detail');
        $model->deleteProduct($post);
        $container_id = $post['container_id'];
        $this->setRedirect('index.php?tmpl=component&option=com_redshop&view=container_detail&layout=products&task=edit&cid[]=' . $container_id);
    }

    public function apply()
    {
        $this->save(1);
    }

    public function save($apply = 0)
    {
        $post = $this->input->getArray($_POST);

        $post["container_desc"] = $this->input->post->getString('container_desc', '');

        $option = $this->input->get('option');

        $post ['creation_date'] = strtotime($post ['creation_date']);

        $model = $this->getModel('container_detail');

        if ($row = $model->store($post))
        {

            $msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_CONTAINER_DETAIL');
        }

        //-------------- Stockroom Container Add --------------------

        if (isset($post['showbuttons']))
        {
            ?>
        <script language="javascript" type="text/javascript">
                <?php
                if (isset($post['showbuttons']))
                {
                    $link = 'index.php?option=' . $option . '&view=stockroom_detail&task=edit&cid[]=' . $post['stockroom_id'];
                }
                ?>
            window.parent.document.location = '<?php echo $link; ?>';
        </script>
        <?php
            exit;
        }

        //-------------- End Stockroom Container Add ----------------
        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=container_detail&task=edit&cid[]=' . $row->container_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
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

        $model = $this->getModel('container_detail');

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
    }

    public function publish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('container_detail');

        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_PUBLISHED_SUCCESFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
    }

    public function unpublish()
    {

        $option = $this->input->get('option');

        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('container_detail');
        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }
        $msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_UNPUBLISHED_SUCCESFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
    }

    public function cancel()
    {
        $option = $this->input->get('option');
        $model  = $this->getModel('container_detail');

        $model->cancel();
        $msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
    }
}
