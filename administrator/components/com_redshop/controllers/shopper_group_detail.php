<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class shopper_group_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'shopper_group';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $option                     = $this->input->get('option');
        $cid                        = $this->input->post->get('cid', array(0), 'array');
        $post                       = $this->input->getArray($_POST);
        $post["shopper_group_desc"] = $this->input->post->getString('shopper_group_desc', '');
        $post["shopper_group_url"]  = "";
        $post["shopper_group_id"]   = $cid [0];

        if (isset($post['shopper_group_categories']) && count($post['shopper_group_categories']) > 0)
        {
            $post["shopper_group_categories"] = implode(",", $post['shopper_group_categories']);
        }
        else
        {
            $post["shopper_group_categories"] = "";
        }

        if (isset($post['shopper_group_manufactures']) && count($post['shopper_group_manufactures']) > 0)
        {
            $post["shopper_group_manufactures"] = implode(",", $post['shopper_group_manufactures']);
        }
        else
        {
            $post["shopper_group_manufactures"] = "";
        }

        $model = $this->getModel('shopper_group_detail');
        $row   = $model->store($post);
        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHOPPER_GROUP_DETAIL');
        }
        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=shopper_group_detail&cid[]=' . $row->shopper_group_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=shopper_group', $msg);
        }
    }

    public function remove()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        if (!is_array($cid) && ($cid == 1 || $cid == 2))
        {
            $msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
        }

        else if (in_array(1, $cid))
        {
            $msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
        }

        else if (in_array(2, $cid))
        {
            $msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
        }

        else
        {
            $model = $this->getModel('shopper_group_detail');
            if (!$model->delete($cid))
            {
                echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
            }

            $msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_DETAIL_DELETED_SUCCESSFULLY');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=shopper_group', $msg);
    }
}

