<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class RedshopControllerTax_group_detail extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'tax_group';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function remove()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        if (!is_array($cid) && $cid == 1)
        {
            $msg = JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_CAN_NOT_BE_DELETED');
        }
        else if (in_array(1, $cid))
        {
            $msg = JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_CAN_NOT_BE_DELETED');
        }
        else
        {

            $model = $this->getModel('tax_group_detail');
            if (!$model->delete($cid))
            {
                echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
            }
            $msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_DELETED_SUCCESSFULLY');
        }

        $this->setRedirect('index.php?option=' . $option . '&view=tax_group', $msg);
    }
}
