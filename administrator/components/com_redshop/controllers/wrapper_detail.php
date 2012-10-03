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

class wrapper_detailController extends RedshopCoreControllerDetail
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');

        // Set the redirect view.
        $product_id             = $this->input->get('product_id');
        $showall                = $this->input->get('showall', '0');
        $this->redirectViewName = 'wrapper&showall=' . $showall . '&product_id=' . $product_id;
    }

    public function save($apply = 0)
    {
        $showall = $this->input->get('showall', '0');
        $page    = "";
        if ($showall)
        {
            $page = "3";
        }
        $post                = $this->input->getArray($_POST);
        $post['product_id']  = (isset($post['container_product'])) ? $post['container_product'] : 0;
        $option              = $this->input->get('option');
        $product_id          = $this->input->getInt('product_id', 0);
        $cid                 = $this->input->post->get('cid', array(0), 'array');
        $post ['wrapper_id'] = $cid [0];

        $model = $this->getModel('wrapper_detail');
        if ($model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_WRAPPER_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_WRAPPER_DETAIL');
        }
        $this->setRedirect('index' . $page . '.php?option=' . $option . '&view=wrapper&showall=' . $showall . '&product_id=' . $product_id, $msg);
    }

    public function enable_defaultpublish()
    {
        $showall = $this->input->get('showall', '0');
        $page    = "";
        if ($showall)
        {
            $page = "3";
        }
        $option     = $this->input->get('option');
        $product_id = $this->input->get('product_id');
        $cid        = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('wrapper_detail');
        if (!$model->enable_defaultpublish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_USE_TO_ALL_ENABLE_SUCCESSFULLY');
        $this->setRedirect('index' . $page . '.php?option=' . $option . '&view=wrapper&showall=' . $showall . '&product_id=' . $product_id, $msg);
    }

    public function enable_defaultunpublish()
    {
        $showall = $this->input->get('showall', '0');
        $page    = "";
        if ($showall)
        {
            $page = "3";
        }
        $option     = $this->input->get('option');
        $product_id = $this->input->get('product_id');
        $cid        = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('wrapper_detail');
        if (!$model->enable_defaultpublish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_USE_TO_ALL_DISABLE_SUCCESSFULLY');
        $this->setRedirect('index' . $page . '.php?option=' . $option . '&view=wrapper&showall=' . $showall . '&product_id=' . $product_id, $msg);
    }
}
