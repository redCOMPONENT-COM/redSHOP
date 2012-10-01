<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'template.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class shipping_box_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'shipping_box';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');

        $model = $this->getModel('shipping_box_detail');
        $row   = $model->store($post);
        if ($row)
        {

            $msg = JText::_('COM_REDSHOP_SHIPPING_BOX_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_BOX');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=shipping_box_detail&task=edit&cid[]=' . $row->shipping_box_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=shipping_box', $msg);
        }
    }
}
