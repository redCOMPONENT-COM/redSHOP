<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerWrapper extends RedshopController
{
    /**
     * Proxy for getModel
     *
     * @param string $name The model name. Optional.
     * @param string $prefix The class prefix. Optional.
     * @param array $config The array of possible config values. Optional.
     *
     * @return  object  The model.
     */
    public function getModel($name = 'Wrapper_detail', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    /**
     * @throws Exception
     */
    public function remove()
    {
        $showAll = $this->input->get('showall', '0');
        $tmpl = '';

        if ($showAll) {
            $tmpl = '&tmpl=component';
        }

        $productId = $this->input->get('product_id');
        $wrapperIds = $this->input->post->get('cid', [0], 'array');

        if (!is_array($wrapperIds) || count($wrapperIds) < 1) {
            \JFactory::getApplication()->enqueueMessage(
                \JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'), 'warning');
        }

        $result = \Redshop\Wrapper\Helper::removeWrappers($wrapperIds);

        if ($result == true) {
            $msg = \JText::_('COM_REDSHOP_WRAPPER_DETAIL_DELETED_SUCCESSFULLY');
        }

        $this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showAll . $tmpl
            . '&product_id=' . $productId, $msg);
    }

    /**
     * @throws Exception
     */
    public function publish()
    {
        $showAll = $this->input->get('showall', '0');
        $tmpl = '';

        if ($showAll) {
            $tmpl = '&tmpl=component';
        }

        $productId = $this->input->get('product_id');
        $wrapperIds = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($wrapperIds) || count($wrapperIds) < 1) {
            throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('wrapper_detail');

        if (!$model->publish($wrapperIds, 1)) {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_WRAPPER_PUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showAll . $tmpl . '&product_id=' . $productId, $msg);
    }

    /**
     * logic for unpublish
     *
     * @access public
     * @return void
     */
    public function unpublish()
    {
        $showAll = $this->input->get('showall', '0');
        $tmpl = '';

        if ($showAll) {
            $tmpl = '&tmpl=component';
        }

        $productId = $this->input->get('product_id');
        $wrapperIds = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($wrapperIds) || count($wrapperIds) < 1) {
            throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('wrapper_detail');

        if (!$model->publish($wrapperIds, 0)) {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_WRAPPER_UNPUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showAll . $tmpl . '&product_id=' . $productId, $msg);
    }

    public function enableWrapperUseToAll()
    {
        $showAll = $this->input->get('showall', '0');
        $tmpl = '';

        if ($showAll) {
            $tmpl = '&tmpl=component';
        }

        $productId = $this->input->get('product_id');
        $wrapperIds = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($wrapperIds) || count($wrapperIds) < 1) {
            throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('wrapper_detail');

        if (!$model->enableWrapperUseToAll($wrapperIds, 1)) {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_USE_TO_ALL_ENABLE_SUCCESSFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showAll . $tmpl . '&product_id=' . $productId, $msg);
    }

    public function enable_defaultunpublish()
    {
        $showAll = $this->input->get('showall', '0');
        $tmpl = '';

        if ($showAll) {
            $tmpl = '&tmpl=component';
        }

        $productId = $this->input->get('product_id');
        $wrapperIds = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($wrapperIds) || count($wrapperIds) < 1) {
            throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('wrapper_detail');

        if (!$model->enableWrapperUseToAll($wrapperIds, 0)) {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_USE_TO_ALL_DISABLE_SUCCESSFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showAll . $tmpl . '&product_id=' . $productId, $msg);
    }
}
