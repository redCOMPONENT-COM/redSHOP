<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'default.php';

class RedshopControllerShipping_rate extends RedshopCoreControllerDefault
{
    public function cancel()
    {
        $post = $this->input->getArray($_POST);
        $this->setRedirect('index.php?option=' . $post['option'] . '&view=shipping_detail&task=edit&cid[]=' . $post['id']);
    }

    public function remove()
    {
        parent::remove();

        $id = $this->input->post->get('id');
        $this->setRedirect('index.php?option=com_redshop&view=shipping_rate&id=' . $id);
    }

    public function copy()
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $model = $this->getModel('shipping_rate_detail');
        if ($model->copy($cid))
        {
            $msg = JText::_('COM_REDSHOP_SHIPPING_RATE_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id'], $msg);
    }
}
