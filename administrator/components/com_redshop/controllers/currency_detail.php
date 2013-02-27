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

class RedshopControllerCurrency_detail extends RedshopCoreController
{
    public $redirectViewName = 'currency';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post                  = $this->input->getArray($_POST);
        $post["currency_name"] = $this->input->post->getString('currency_name', '');
        $option                = $this->input->get('option');
        $cid                   = $this->input->post->get('cid', array(0), 'array');
        $post ['currency_id']  = $cid [0];
        $model                 = $this->getModel('currency_detail');
        $row                   = $model->store($post);

        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_CURRENCY_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_CURRENCY_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=currency_detail&task=edit&cid[]=' . $row->currency_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=currency', $msg);
        }
    }
}
